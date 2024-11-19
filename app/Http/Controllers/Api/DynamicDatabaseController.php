<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;

class DynamicDatabaseController extends Controller
{
    /**
     * List of allowed tables to prevent SQL injection
     */
    protected $allowedTables = [
        'users',
        'posts',
        'comments',
        'likes',
        'messages',
        'shares',
        'followers'
    ];

    /**
     * Validate if the requested table is allowed
     */
    protected function validateTable($table)
    {
        if (!in_array($table, $this->allowedTables)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid table name'
            ], 400);
        }

        if (!Schema::hasTable($table)) {
            return response()->json([
                'success' => false,
                'message' => 'Table does not exist'
            ], 404);
        }

        return true;
    }

    /**
     * Get all records from a table with optional query parameters
     */
    public function index(Request $request, $table)
    {
        $validation = $this->validateTable($table);
        if ($validation !== true) {
            return $validation;
        }

        try {
            $query = DB::table($table);

            // Apply filters from query parameters
            foreach ($request->query() as $column => $value) {
                if (Schema::hasColumn($table, $column)) {
                    $query->where($column, $value);
                }
            }

            $results = $query->get();

            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => "Records retrieved successfully from {$table}"
            ]);
        } catch (QueryException $e) {
            return $this->handleQueryException($e);
        }
    }

    /**
     * Get a specific record from a table
     */
    public function show(Request $request, $table, $id)
    {
        $validation = $this->validateTable($table);
        if ($validation !== true) {
            return $validation;
        }

        try {
            $record = DB::table($table)->find($id);

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => "Record not found in {$table}"
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $record,
                'message' => "Record retrieved successfully from {$table}"
            ]);
        } catch (QueryException $e) {
            return $this->handleQueryException($e);
        }
    }

    /**
     * Create a new record in a table
     */
    public function store(Request $request, $table)
    {
        $validation = $this->validateTable($table);
        if ($validation !== true) {
            return $validation;
        }

        try {
            // Get table columns excluding timestamps and auto-increment
            $columns = Schema::getColumnListing($table);
            $columns = array_diff($columns, ['id', 'created_at', 'updated_at']);

            // Validate input data against table columns
            $validator = Validator::make($request->all(), 
                collect($columns)->mapWithKeys(function ($column) use ($table) {
                    return [$column => 'required'];
                })->toArray()
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $id = DB::table($table)->insertGetId($request->only($columns));

            return response()->json([
                'success' => true,
                'data' => ['id' => $id],
                'message' => "Record created successfully in {$table}"
            ], 201);
        } catch (QueryException $e) {
            return $this->handleQueryException($e);
        }
    }

    /**
     * Update a specific record in a table
     */
    public function update(Request $request, $table, $id)
    {
        $validation = $this->validateTable($table);
        if ($validation !== true) {
            return $validation;
        }

        try {
            $record = DB::table($table)->find($id);

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => "Record not found in {$table}"
                ], 404);
            }

            // Get table columns excluding timestamps and auto-increment
            $columns = Schema::getColumnListing($table);
            $columns = array_diff($columns, ['id', 'created_at', 'updated_at']);

            // Update only provided columns
            $updateData = array_intersect_key($request->all(), array_flip($columns));
            
            if (empty($updateData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid columns to update'
                ], 422);
            }

            DB::table($table)->where('id', $id)->update($updateData);

            return response()->json([
                'success' => true,
                'message' => "Record updated successfully in {$table}"
            ]);
        } catch (QueryException $e) {
            return $this->handleQueryException($e);
        }
    }

    /**
     * Delete a specific record from a table
     */
    public function destroy(Request $request, $table, $id)
    {
        $validation = $this->validateTable($table);
        if ($validation !== true) {
            return $validation;
        }

        try {
            $record = DB::table($table)->find($id);

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => "Record not found in {$table}"
                ], 404);
            }

            DB::table($table)->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => "Record deleted successfully from {$table}"
            ]);
        } catch (QueryException $e) {
            return $this->handleQueryException($e);
        }
    }

    /**
     * Handle database query exceptions
     */
    protected function handleQueryException(QueryException $e)
    {
        \Log::error('Database error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Database operation failed',
            'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
        ], 500);
    }
}