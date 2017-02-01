@extends('emails.master')

@section('mail_title', 'Error en cuotas de mantenimiento')

@section('mail')
    <tr>
        <td align="center" bgcolor="#a5084d" style="padding: 25px 20px 20px 20px; color: #ffffff; font-family: Arial, sans-serif; font-size: 26px;">
            <b>Error al generar cuotas de mantenimiento</b>
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" style="padding: 20px 20px 20px 20px; color: #555555; font-family: Arial, sans-serif; font-size: 15px; line-height: 24px;">
            <p>
                ¡Hola!
            </p>
            <br />
            <p>
                Ocurrió un error al generar las cuotas de mantenimiento para la fecha: {{ $year }} - {{ getMonth($month) }}
            </p>
            <br />
            <table border="1" width="100%" cellpadding="5" cellspacing="0" bordercolor="#dddddd">
                <thead>
                <tr>
                    <th style="text-align: left;padding: 5px;">ID</th>
                    <th style="text-align: left;padding: 5px;">Número oficial</th>
                    <th style="text-align: left;padding: 5px;">Propietario</th>
                    <th style="text-align: left;padding: 5px;">Clave catastral</th>
                    <th style="text-align: left;padding: 5px;">Cuenta</th>
                    <th style="text-align: left;padding: 5px;">M2</th>
                </tr>
                </thead>
                    @foreach($lots as $lot)
                        <tr>
                            <td>{{ $lot->id }}</td>
                            <td>{{ $lot->official_number }}</td>
                            <td>{{ $lot->owner }}</td>
                            <td>{{ $lot->cadastral_key }}</td>
                            <td>{{ $lot->account_number }}</td>
                            <td>{{ $lot->m2 }}</td>
                        </tr>
                    @endforeach
                <tbody>

                </tbody>
            </table>

            <br />

        </td>
    </tr>

@endsection