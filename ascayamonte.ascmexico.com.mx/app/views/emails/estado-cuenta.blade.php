@extends('emails.master')

@section('mail_title', 'Estado de cuenta')

@section('mail')
    <tr>
        <td align="center" bgcolor="#a5084d" style="padding: 25px 20px 20px 20px; color: #ffffff; font-family: Arial, sans-serif; font-size: 26px;">
            <b>Estado de cuenta - {{ getMonth($month) }}/{{ $year }}</b>
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" style="padding: 20px 20px 20px 20px; color: #555555; font-family: Arial, sans-serif; font-size: 15px; line-height: 24px;">
            <p>
                ¡Hola {{ $name }}!
            </p>
            <br />
            <p>
                Tu estado de cuenta se ha generado para el mes de <strong>{{ getMonth($month) }}</strong> del año <strong>{{ $year }}</strong>, puedes descargarlo dando clic en el siguiente enlace:<!-- para el mes de Abril de 2016.-->
            </p>
            {{--<br />--}}
            <!--table border="0" width="100%" cellpadding="5" cellspacing="0">
                <tr>
                    <td style="padding: 5px;"><strong>Fecha de solicitud:</strong></td>
                    <td style="padding: 5px;"></td>
                </tr>
                <tr>
                    <td style="padding: 5px;"><strong>Estatus:</strong></td>
                    <td style="padding: 5px;"></td>
                </tr>
            </table>
            <br />
            <table border="1" width="100%" cellpadding="5" cellspacing="0" bordercolor="#dddddd">
                <thead>
                <tr>
                    <th style="text-align: left;padding: 5px;">Modelo</th>
                    <th style="text-align: left;padding: 5px;">VIN</th>
                    <th style="text-align: left;padding: 5px;">Chasis</th>
                    <th style="text-align: left;padding: 5px;">Color</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            <br /-->

        </td>
    </tr>
    <tr>
        <td align="center" bgcolor="#f9f9f9" style="padding: 30px 20px 10px 20px; font-family: Arial, sans-serif;">
            <table bgcolor="#a5084d" border="0" cellspacing="0" cellpadding="0" class="buttonwrapper">
                <tr>
                    <td align="center" height="55" style=" padding: 0 35px 0 35px; font-family: Arial, sans-serif; font-size: 22px;" class="button">
                        <a href="{{ route('lots.download', $lot->id) }}" style="color: #ffffff; text-align: center; text-decoration: none;">Descargar PDF</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection