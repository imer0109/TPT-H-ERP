<?php

namespace App\Http\Controllers;

use App\Models\UserSession;
use Illuminate\Http\Request;

class UserSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = UserSession::with(['user']);
        
        // Apply user filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }
        
        // Apply device type filter
        if ($request->filled('device_type')) {
            $query->where('device_type', $request->get('device_type'));
        }
        
        // Apply platform filter
        if ($request->filled('platform')) {
            $query->where('platform', $request->get('platform'));
        }
        
        // Apply browser filter
        if ($request->filled('browser')) {
            $query->where('browser', $request->get('browser'));
        }
        
        // Apply suspicious filter
        if ($request->filled('is_suspicious')) {
            $query->where('is_suspicious', $request->get('is_suspicious'));
        }
        
        // Apply active filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }
        
        // Apply date range filter
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->get('start_date'));
        }
        
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->get('end_date') . ' 23:59:59');
        }
        
        $userSessions = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->except('page'));
        
        // Get filter options
        $users = \App\Models\User::all();
        $deviceTypes = ['desktop', 'mobile', 'tablet'];
        $platforms = ['Windows', 'macOS', 'Linux', 'Android', 'iOS'];
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];
        
        return view('user-sessions.index', compact('userSessions', 'users', 'deviceTypes', 'platforms', 'browsers'));
    }
    
    public function show($id)
    {
        $userSession = UserSession::with(['user'])->findOrFail($id);
        
        return view('user-sessions.show', compact('userSession'));
    }
    
    public function destroy($id)
    {
        $userSession = UserSession::findOrFail($id);
        $userSession->forceLogout('admin_action');
        
        return redirect()->back()->with('success', 'Session terminée avec succès.');
    }
}