<!DOCTYPE html>
				<html lang="en">
				<head>
        		</head>
				<body style="padding: 0 5% ;margin: 0 5%;color:dimgrey;font-family: Arial, Helvetica, sans-serif;">
					<table style="width:100%">
						<tr>
							<td style="text-align: center;font-weight: bold;" colspan="12">
								<h2 ><img style="vertical-align:text-bottom;" src="reportes/icono-beraca.png" /> RECETA DE LENTES</h2>
								<hr style="border: dimgrey 2px solid;text-align: center;">
							</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<!-- DATOS PERSONALES -->
						<tr>
							<td colspan="12"><span style="padding: 0 ;margin: 0 ; font-size: 16px;font-weight: bold;color: #fff;background-color: #777;"><label>
								NOMBRE : 
							</label></span>&nbsp;'.$row[0]['NOMBRE'].'</td>

						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="4"><span style="padding: 0 ;margin: 0 ; font-size: 16px;font-weight: bold;color: #fff;background-color: #777;"><label>
                                RUT :  </label></span>&nbsp;'. $row[0]['RUT_CLIENTE'].'</td>

                            <td colspan="4"><span style="padding: 0 ;margin: 0 ; font-size: 16px;font-weight: bold;color: #fff;background-color: #777;"><label>
                                TELÉFONO :  </label></span>&nbsp;'. $row[0]['TELEFONO'] .'</td>

                            <td colspan="4"><span style="padding: 0 ;margin: 0 ; font-size: 16px;font-weight: bold;color: #fff;background-color: #777;"><label>
                                EDAD : </label></span>&nbsp;</td>

						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<!-- LEJOS -->
						<tr>
							<td colspan="12">
								<h2 style="text-align: left;font-weight: bold;color: dimgrey;font-size: 1.5em;">LEJOS</h2>
								<hr style="border: dimgrey 2px solid;text-align: center;">
							</td>
						</tr>
						<tr>
							<td colspan="11">
								<table  style="border: 2px solid dimgrey; text-align: center;width:100%">
									<tr>
										<td style="background-color: dimgrey;color:white;">&nbsp;</td>
										<td style="background-color: dimgrey;color:white;">ESFERICO</td>
										<td style="background-color: dimgrey;color:white;">CILINDRO</td>
										<td style="background-color: dimgrey;color:white;">EJE</td>
									</tr>
									<tr>
										<td style="background-color: dimgrey;color:white;">O.I.</td>
										<td>s</td>
										<td>s</td>
										<td>s</td>
									</tr>
									<tr>
										<td style="background-color: dimgrey;color:white;">O.D.</td>
										<td>s</td>
										<td>s</td>
										<td>s</td>
									</tr>
								</table>
							</td>
							<td colspan="1" style="vertical-align:text-bottom;">
								<table class="table table-bordered"  style="border: 2px solid dimgrey; text-align: center ;width:100%; ">
									<tr>
										<td style="background-color: dimgrey;color:white;">D.P.</td>
									</tr>
									<tr>
										<td>s</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<!-- CERCA -->
						<tr>
							<td colspan="12">
								<h2 style="text-align: left;font-weight: bold;color: dimgrey;font-size: 1.5em;">CERCA</h2>
								<hr style="border: dimgrey 2px solid;text-align: center;">
							</td>
						</tr>
						<tr>
							<td colspan="11">
								<table  style="border: 2px solid dimgrey; text-align: center;width:100%">
									<tr>
										<td style="background-color: dimgrey;color:white;">&nbsp;</td>
										<td style="background-color: dimgrey;color:white;">ESFERICO</td>
										<td style="background-color: dimgrey;color:white;">CILINDRO</td>
										<td style="background-color: dimgrey;color:white;">EJE</td>
									</tr>
									<tr>
										<td style="background-color: dimgrey;color:white;">O.I.</td>
										<td>s</td>
										<td>s</td>
										<td>s</td>
									</tr>
									<tr>
										<td style="background-color: dimgrey;color:white;">O.D.</td>
										<td>s</td>
										<td>s</td>
										<td>s</td>
									</tr>
								</table>
							</td>
							<td colspan="1" style="vertical-align:text-bottom;">
								<table class="table table-bordered"  style="border: 2px solid dimgrey; text-align: center ;width:100%; ">
									<tr>
										<td style="background-color: dimgrey;color:white;">D.P.</td>
									</tr>
									<tr>
										<td>s</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
						<!-- OBSERVACION -->
						<tr>
							<td colspan="12">
								<h2 style="style="text-align: left;font-weight: bold;color: dimgrey;font-size: 1.5em;">OBSERVACIÓN</h2>
								<hr style="border: dimgrey 2px solid;text-align: center; ">
							</td>
						</tr>
						<tr>
							<td colspan="12"  style="border: 2px solid dimgrey; text-align: center; width:100%; height: 200px">
								 <div>s</div>
							</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>
						 <!-- FIRMA  -->
						 <tr>
							<td colspan="6"><div>FECHA: '.date("d/m/Y").'</div></td>
							<td colspan="3"></td>
							<td colspan="3" style="border-top:2px solid dimgrey; text-align: center;font-weight: bold;"><div >FIRMA</div></td>
						</tr>
					   
						<tr>
							<td  colspan="12">&nbsp;</td>
						</tr>  
					</table>
				 
					   
				   
				</body>
				</html>