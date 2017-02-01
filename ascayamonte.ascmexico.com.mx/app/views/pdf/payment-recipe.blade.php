<!DOCTYPE html>
<html lang="es-MX">
	<head>
		<meta charset="utf-8" />

		<style>
			html {
				margin: 20px;
			}

			body {
				font-family: Arial, sans-serif;
			}

			p {
				font-size: 13px;
			}

			.text-justify {
				text-align: justify;
			}

			.text-center {
				text-align: center;
			}

			.text-right {
				text-align: right;
			}

			.format {
				/*border: 2px solid black;*/
				border-collapse: collapse;
				width: 100%;
			}

			.format p {
				font-size: 13px;
			}

			.no-border {
				border: none;
			}

			.border-top {
				border-top: 1px solid black;
			}

			.bg-gray {
				background: darkgray;
				color: white;
			}

			td {
				padding: 5px 10px;
			}

			.title-main {
				font-size: 10px;
				margin: 0;
				padding: 0;
			}

			.header {
				width: 100%;
			}

			.header p {
				font-size: 10px;
				line-height: 40%;
			}

			.tag {
				padding: 3px 5px;
				display: block;
				font-size: 13px;
				line-height: 100%;
				text-align: center;
			}

			.tag-gray {
				color: white;
				background: darkgray;
			}

			.tag-blue {
				color: black;
				background: lightskyblue;
			}

			.uppercase {
				text-transform: uppercase;
			}
		</style>
	</head>

	<body>
		<pre>
		<?php
			//	variables
			$amount = substr( $payment -> amount, 0, -2  ) . '.' . strval( substr( $payment -> amount, -2 ) );
			$date = date( 'd/m/Y', strtotime( $payment -> updated_at ) );
			$zipcode = isset( $address -> zipcode ) ? $address -> zipcode : '';

			if( is_null( $address ) )
				$full = '';
			else
				$full = 'Calle ' . $address -> address . ', Col. ' . $address -> neighborhood . ', ' . $address -> city . ', ' . $address -> state ;
		?>
		</pre>
		<p class="title-main text-center"><strong>RECIBO DE PAGO</strong></p>

		<table class="header">
			<tr>
				<td width="50%">
					<br />
					<img height="100px" src="{{ public_path() . '/assets/admin/img/logo.jpg' }}" alt="Ayamonte" />
				</td>
				<td class="clean" width="35%">
					<h5>Condominio Compuesto Ayamonte</h5>
					<h4>RFC: <strong>CCA080912D74</strong></h4>
					<p>Av. de las Artes No. 60 Zapopan Jal. CP 45019</p>
					<p>Tel. 3627-8795</p>
					<p>E-mail: administracion@ayamonte.mx</p>
				</td>
				<td class="clean" width="15%">
					<p class="text-center">Recibo Nº</p>
					<div class="clean tag tag-gray">{{  $payment -> id }}</div>
					<p class="text-center">Expedido</p>
					<div class="clean tag tag-blue">{{ $date }}</div>
					<h5 class="text-center">Zapopan Jal.</h5>
				</td>
			</tr>
		</table>

		<table class="format" border="1">
			<tr>
				<td class="bg-gray"><strong>Recibimos de:</strong></td>
				<td colspan="3"></td>
			</tr>
			<tr>
				<td class="no-border text-center" width="20%">
					<p>Cuenta Nº</p>
					<h2>{{  $lot -> official_number }}</h2>
				</td>
				<td class="no-border" width="55%" colspan="3">
					<p></p><strong class="uppercase">{{  $lot -> owner }}</strong></p>
					<br />
					<p>{{ $full }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>{{ $zipcode }}</strong></p>
				</td>
				{{--<td class="no-border" width="25%"></td>--}}
			</tr>
			<tr>
				<td class="bg-gray"><strong>Descripción:</strong></td>
				<td colspan="2"></td>
				<td></td>
			</tr>

			<tr>
				<td class="no-border">
					<p>{{ is_null( $payment -> concept ) ? 'Pago' : $payment -> concept }}</p>
				</td>
				<td class="no-border" width="50%"></td>
				<td class="no-border text-right"><p><strong>$</strong></p></td>
				<td class="no-border text-right"><p>{{ number_format($amount, 2); }}</p></td>
			</tr>

			{{--<tr>--}}
				{{--<td class="no-border" colspan="4">--}}
					{{--<br />--}}
				{{--</td>--}}
			{{--</tr>--}}

			<tr>
				<td class="no-border" colspan="4">
					<p>Método de pago: <strong>{{ getPaymentType( $payment -> type ) }}</strong></p>
					<p>El pago del presente recibo <strong>NO</strong> libera de otros adeudos</p>
				</td>
			</tr>

			<tr>
				<td class="no-border"></td>
				<td class="no-border" width="50%"></td>
				<td class="no-border text-right"><p><strong>Total $</strong></p></td>
				<td class="no-border text-right"><p>{{ number_format($amount, 2); }}</p></td>
			</tr>

			<tr>
				<td class="no-border text-right" colspan="4">
					<p>{{ num2letras( $amount )  }}</p>
					{{--<p>Tres mil pesos 00/100 M.N.</p>--}}
				</td>
			</tr>

			<tr>
				<td class="bg-gray"><strong>Observaciones:</strong></td>
				<td colspan="3"></td>
			</tr>

			<tr>
				<td class="no-border text-center"><p>Plaza {{ getSquareById( $lot -> square_id ) }}</p></td>
				<td class="no-border text-center"><p>Lote Nº&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $lot -> lot }}</p></td>
				<td class="no-border text-center" colspan="2"><p>Superficie&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $lot-> m2 }} m<sup>2</sup></p></td>
			</tr>

			<tr>
				<td class="no-border"></td>
				<td class="no-border text-center" colspan="2"><p>Cubre el periodo del:</p></td>
				<td class="no-border"></td>
			</tr>

			<tr>
				<td class="no-border border-top"></td>
				<td class="no-border border-top">
					<h1 style="font-size: 15px;">PAGADO</h1>
				</td>
				<td class="no-border border-top text-center">
					<p>Administrador</p>
					<br />
					<p>Hugo Carlos Fuentevilla</p>
				</td>
				<td class="no-border border-top"></td>
			</tr>
		</table>
		<br />
		<p class="text-justify">El pago se puede hacer en las oficinas del Fraccionamiento o en cualquier sucursal Scotiabank depositando a la cuenta <strong>01001946765</strong> o traspaso a cuenta Scotiabank CLABE <strong>044320010019467658</strong> a nombre de CONDOMINIO COMPUESTO AYAMONTE.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Referencia bancaria:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0</p>
	</body>
</html>