<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidationDeleteNotification;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function indexNotification()
    {
        return view('notification');
    }

    public function changeStatusNotification(Request $request)
    {
        $notification = Notification::where('id', $request->id);
        $notification->update(['status' => 'read']);
        return response()->json(
            $notification->get(['*'])
                ->map(function ($notification) {
                    $notification->path=route($notification->path);
                    return   $notification;
                })
                ->values()
                ->all()
        );
    }
    public function dataTable(Request $request)
    {
        $notification = new Notification();
        $notifications = $notification->getNotificationsDataTable($request,auth()->user()->id,auth()->user()->id_rol);
        return response()->json($notifications);
    }

    public function destroy(ValidationDeleteNotification $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $idsNotifications = $request['id'];
        foreach ($idsNotifications as $key => $id) {

            if (Notification::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

        return $cantSuccsess <= 1 ?
            redirect('notification')->with('success', $cantSuccsess . ' notificación eliminada con éxito.')
            :
            redirect('notification')->with('success', $cantSuccsess . ' notificaciones eliminadas con éxito.');
    }
}
