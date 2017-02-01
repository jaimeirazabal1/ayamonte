<?php

/**
 * Obtiene los datos enviados por el Usuario
 *
 * @return array
 */
function getApiInput()
{
    $data = file_get_contents('php://input');
    $data = json_decode($data, true);

    return $data;
}


/**
 * @return array
 */
function getDayOptions()
{
    $days = [
        '' => 'Día'
    ];
    for($i = 1; $i <= 31; $i++)
    {
        $days[$i] = $i;
    }

    return $days;
}

/**
 * @return array
 */
function getYearOptions()
{
    $years = [
        '' => 'Año'
    ];
    for($i = date('Y'); $i >= 1920; $i--)
    {
        $years[$i] = $i;
    }

    return $years;
}

/**
 *
 */
function getMonthOptions()
{
    $months = [
        ''  => 'Mes',
        '1' => 'Enero',
        '2' => 'Febrero',
        '3' => 'Marzo',
        '4' => 'Abril',
        '5' => 'Mayo',
        '6' => 'Junio',
        '7' => 'Julio',
        '8' => 'Agosto',
        '9' => 'Septiembre',
        '10' => 'Octubre',
        '11' => 'Noviembre',
        '12' => 'Diciembre'
    ];

    return $months;
}



/**
 * @param $month
 * @return mixed
 */
function getMonth($month)
{
    $months = getMonthOptions();

    return $months[$month];
}

/**
 * @param $month
 * @return string
 */
function getShortMonth($month)
{
    $months = getMonthOptions();

    return substr($months[$month], 0, 3);
}

/**
 * @param $status
 * @return string
 */
function getAppointmentStatus($status)
{
    $status_text = '';

    switch($status)
    {
        case 'pending':
            $status_text = 'Pendiente';
            break;
        case 'confirmed':
            $status_text = 'Confirmada';
            break;
        case 'canceled':
            $status_text = 'Cancelada';
            break;
        case 'completed':
            $status_text = 'Completada';
            break;
        case 'process':
            $status_text = 'En proceso';
            break;
        default:
            $status_text = 'No definido';
            break;
    }

    return $status_text;
}


/**
 * Convierte una cantidad monetaria en entero
 *
 * @param $amount
 * @return float
 */
function convertMoneyToInteger($amount)
{
    $amount = (float) str_replace(',', '', $amount); # Elimina las comas
    return ($amount * 100);
}

/**
 * Convierte un número entero a su representación monetaria
 *
 * @param $amount
 * @return string
 */
function convertIntegerToMoney($amount)
{
    return number_format($amount / 100, 2);
}


/**
 * Convierte un valor númerico a formato en efectivo
 *
 * @param $amount
 * @return string
 */
function formatToMoney($amount)
{
    $amount = (float) str_replace(',', '', $amount); # Elimina las comas
    return number_format($amount, 2);
}

/**
 * Convierte un valor númerico a formato en efectivo
 *
 * @param $amount
 * @return string
 */
function formatToMeters($amount)
{
    $amount = (float) str_replace(',', '', $amount); # Elimina las comas
    return $amount;
}




/**
 * @return array
 */
function getScheduleDays()
{
    return [
        'MONDAY' => 'Lunes',
        'TUESDAY' => 'Martes',
        'WEDNESDAY' => 'Miercoles',
        'THURSDAY' => 'Jueves',
        'FRIDAY' => 'Viernes',
        'SATURDAY' => 'Sábado',
        'SUNDAY' => 'Domingo'
    ];
}

/**
 * @param $day
 * @return mixed
 */
function getDayOfWeek($day)
{
    $days = [
        0 => 'SUNDAY',
        1 => 'MONDAY',
        2 => 'TUESDAY',
        3 => 'WEDNESDAY',
        4 => 'THURSDAY',
        5 => 'FRIDAY',
        6 => 'SATURDAY'
    ];

    return $days[$day];
}


/**
 * @param $status
 * @return string
 */
function getTransactionStatus($status)
{

    $status_text = '';

    switch($status)
    {
        case 'pending':
            $status_text = 'Pendiente';
            break;
        case 'paid':
            $status_text = 'Pagado';
            break;
        case 'partially_paid':
            $status_text = 'Pago parcial';
            break;
        case 'current':
            $status_text = 'Actual';
            break;
    }

    return $status_text;

}

/**
 * @param $transaction
 * @return string
 */
function getTransactionType($transaction)
{
    $text = '';

    switch($transaction)
    {
        case 'fee_ordinary_maintenance':
            $text = 'Mantenimiento';
            break;
        case 'fee_extraordinary_reserve':
            $text = 'Fondo de reserva';
            break;
        case 'fee_special_work':
            $text = 'Cuota especial para obra';
            break;
        case 'fee_debts_2010':
            $text = 'Cuota adeudo 2010';
            break;
    }

    return $text;
}


function getPaymentTypeOptions()
{
//    Before
//    return [
//        'cash' => 'Efectivo',
//        'deposit' => 'Depósito en efectivo',
//        'transfer' => 'Transferencia',
//        'cheque' => 'Cheque'
//    ];

    //    Change by: Codeman Company
    return [
        'deposit' => 'Depósito en efectivo',
        'card' => 'Tarjeta de crédito o débito',
        'cash' => 'Efectivo',
        'cheque' => 'Cheque',
        'transfer' => 'Transferencia'
    ];
}


function getPaymentType($type)
{
    //    Change by: Codeman Company
    //    $types = getPaymentTypeOptions();
//    $types = [
//        'deposit' => 'Depósito',
//        'card' => 'Tarjeta',
//        'cash' => 'Efectivo',
//        'cheque' => 'Cheque',
//        'transfer' => 'Transferencia'
//    ];

    $types = [
        'deposit' => 'Depósito en efectivo',
        'card' => 'Tarjeta de crédito o débito',
        'cash' => 'Efectivo',
        'cheque' => 'Cheque',
        'transfer' => 'Transferencia'
    ];

    //  Database
    //  ENUM('deposit', 'card', 'cash', 'cheque', 'transfer')

    return $types[$type];
}

/*!
  @function num2letras ()
  @abstract Dado un n?mero lo devuelve escrito.
  @param $num number - N?mero a convertir.
  @param $fem bool - Forma femenina (true) o no (false).
  @param $dec bool - Con decimales (true) o no (false).
  @result string - Devuelve el n?mero escrito en letra.

*/
function num2letras($num, $fem = false, $dec = true) {
    $matuni[2]  = "dos";
    $matuni[3]  = "tres";
    $matuni[4]  = "cuatro";
    $matuni[5]  = "cinco";
    $matuni[6]  = "seis";
    $matuni[7]  = "siete";
    $matuni[8]  = "ocho";
    $matuni[9]  = "nueve";
    $matuni[10] = "diez";
    $matuni[11] = "once";
    $matuni[12] = "doce";
    $matuni[13] = "trece";
    $matuni[14] = "catorce";
    $matuni[15] = "quince";
    $matuni[16] = "dieciseis";
    $matuni[17] = "diecisiete";
    $matuni[18] = "dieciocho";
    $matuni[19] = "diecinueve";
    $matuni[20] = "veinte";
    $matunisub[2] = "dos";
    $matunisub[3] = "tres";
    $matunisub[4] = "cuatro";
    $matunisub[5] = "quin";
    $matunisub[6] = "seis";
    $matunisub[7] = "sete";
    $matunisub[8] = "ocho";
    $matunisub[9] = "nove";

    $matdec[2] = "veint";
    $matdec[3] = "treinta";
    $matdec[4] = "cuarenta";
    $matdec[5] = "cincuenta";
    $matdec[6] = "sesenta";
    $matdec[7] = "setenta";
    $matdec[8] = "ochenta";
    $matdec[9] = "noventa";
    $matsub[3]  = 'mill';
    $matsub[5]  = 'bill';
    $matsub[7]  = 'mill';
    $matsub[9]  = 'trill';
    $matsub[11] = 'mill';
    $matsub[13] = 'bill';
    $matsub[15] = 'mill';
    $matmil[4]  = 'millones';
    $matmil[6]  = 'billones';
    $matmil[7]  = 'de billones';
    $matmil[8]  = 'millones de billones';
    $matmil[10] = 'trillones';
    $matmil[11] = 'de trillones';
    $matmil[12] = 'millones de trillones';
    $matmil[13] = 'de trillones';
    $matmil[14] = 'billones de trillones';
    $matmil[15] = 'de billones de trillones';
    $matmil[16] = 'millones de billones de trillones';

    //Zi hack
    $float=explode('.',$num);
    $num=$float[0];

    $num = trim((string)@$num);
    if ($num[0] == '-') {
        $neg = 'menos ';
        $num = substr($num, 1);
    }else
        $neg = '';
    while ($num[0] == '0') $num = substr($num, 1);
    if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
    $zeros = true;
    $punt = false;
    $ent = '';
    $fra = '';
    for ($c = 0; $c < strlen($num); $c++) {
        $n = $num[$c];
        if (! (strpos(".,'''", $n) === false)) {
            if ($punt) break;
            else{
                $punt = true;
                continue;
            }

        }elseif (! (strpos('0123456789', $n) === false)) {
            if ($punt) {
                if ($n != '0') $zeros = false;
                $fra .= $n;
            }else

                $ent .= $n;
        }else

            break;

    }
    $ent = '     ' . $ent;
    if ($dec and $fra and ! $zeros) {
        $fin = ' coma';
        for ($n = 0; $n < strlen($fra); $n++) {
            if (($s = $fra[$n]) == '0')
                $fin .= ' cero';
            elseif ($s == '1')
                $fin .= $fem ? ' una' : ' un';
            else
                $fin .= ' ' . $matuni[$s];
        }
    }else
        $fin = '';
    if ((int)$ent === 0) return 'Cero ' . $fin;
    $tex = '';
    $sub = 0;
    $mils = 0;
    $neutro = false;
    while ( ($num = substr($ent, -3)) != '   ') {
        $ent = substr($ent, 0, -3);
        if (++$sub < 3 and $fem) {
            $matuni[1] = 'una';
            $subcent = 'as';
        }else{
            $matuni[1] = $neutro ? 'un' : 'uno';
            $subcent = 'os';
        }
        $t = '';
        $n2 = substr($num, 1);
        if ($n2 == '00') {
        }elseif ($n2 < 21)
            $t = ' ' . $matuni[(int)$n2];
        elseif ($n2 < 30) {
            $n3 = $num[2];
            if ($n3 != 0) $t = 'i' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        }else{
            $n3 = $num[2];
            if ($n3 != 0) $t = ' y ' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        }
        $n = $num[0];
        if ($n == 1) {
            $t = ' ciento' . $t;
        }elseif ($n == 5){
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
        }elseif ($n != 0){
            $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
        }
        if ($sub == 1) {
        }elseif (! isset($matsub[$sub])) {
            if ($num == 1) {
                $t = ' mil';
            }elseif ($num > 1){
                $t .= ' mil';
            }
        }elseif ($num == 1) {
            $t .= ' ' . $matsub[$sub] . '?n';
        }elseif ($num > 1){
            $t .= ' ' . $matsub[$sub] . 'ones';
        }
        if ($num == '000') $mils ++;
        elseif ($mils != 0) {
            if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
            $mils = 0;
        }
        $neutro = true;
        $tex = $t . $tex;
    }
    $tex = $neg . substr($tex, 1) . $fin;
    //Zi hack --> return ucfirst($tex);
    $end_num=ucfirst($tex).' pesos '.$float[1].'/100 M.N.';
    return $end_num;
}

function getSquareById( $id ) {
    $squares = [
      1 => 'Literatura', 2 => 'Música', 3 => 'Pintura', 4 => 'Escultura', 5 => 'Cine', 6 => 'Danza', 7 => 'Arquitectura'
    ];
    return $squares[ $id ];
}