@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de Présence</h3>
                    <div class="card-tools">
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Retour</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Employé</th>
                                    <td>{{ $attendance->employee->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Département</th>
                                    <td>{{ $attendance->employee->department->name }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Heure d'Arrivée</th>
                                    <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Heure de Départ</th>
                                    <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        <span class="badge badge-{{ $attendance->status_color }}">
                                            {{ $attendance->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                @if($attendance->late_minutes)
                                <tr>
                                    <th>Retard</th>
                                    <td>{{ $attendance->late_minutes }} minutes</td>
                                </tr>
                                @endif
                                @if($attendance->overtime_hours)
                                <tr>
                                    <th>Heures Supplémentaires</th>
                                    <td>{{ $attendance->overtime_hours }} heures</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Photo d'Arrivée</h5>
                                    @if($attendance->check_in_photo)
                                        <img src="{{ Storage::url($attendance->check_in_photo) }}" alt="Photo d'arrivée" class="img-fluid">
                                    @else
                                        <p>Pas de photo</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5>Photo de Départ</h5>
                                    @if($attendance->check_out_photo)
                                        <img src="{{ Storage::url($attendance->check_out_photo) }}" alt="Photo de départ" class="img-fluid">
                                    @else
                                        <p>Pas de photo</p>
                                    @endif
                                </div>
                            </div>

                            @if($attendance->notes)
                            <div class="mt-4">
                                <h5>Notes</h5>
                                <p>{{ $attendance->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection