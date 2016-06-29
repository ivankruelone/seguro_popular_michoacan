<?php
foreach ($query->result() as $row) {
	if($row->tiporequerimiento == 1)
	{
		$recetas = $row->cuenta;
		$recetasPiezas = $row->cansur;
		$recetasTotal = $row->total;
		$recetasAbasto = $row->abasto;
	}elseif($row->tiporequerimiento == 2)
	{
		$colectivos = $row->cuenta;
		$colectivosPiezas = $row->cansur;
		$colectivosTotal = $row->total;
		$colectivosAbasto = $row->abasto;
	}elseif($row->tiporequerimiento == 3)
	{
		$paquetes = $row->cuenta;
		$paquetesPiezas = $row->cansur;
		$paquetesTotal = $row->total;
		$paquetesAbasto = $row->abasto;
	}
}



?>
					<!-- Folios y Desplazamiento -->

					<div class="row-fluid">
						<div class="span12">

							<div class="row-fluid">
								<div class="span12 infobox-container">

									<div class="infobox infobox-blue">
										<div class="infobox-icon">
											<i class="icon-user"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="recetas"><?php echo number_format($recetas, 0); ?></span>
											<div class="infobox-content">Recetas capturadas</div>
										</div>

									</div>

									<div class="infobox infobox-red">
										<div class="infobox-icon">
											<i class="icon-group"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="colectivos"><?php echo number_format($colectivos, 0); ?></span>
											<div class="infobox-content">Colectivos capturados</div>
										</div>

									</div>

									<div class="infobox infobox-green">
										<div class="infobox-icon">
											<i class="icon-shopping-cart"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="paquetes"><?php echo number_format($paquetes, 0); ?></span>
											<div class="infobox-content">Paquetes capturados</div>
										</div>
									</div>


									<div class="infobox infobox-blue">
										<div class="infobox-icon">
											<i class="icon-user"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="recetasPiezas"><?php echo number_format($recetasPiezas, 0); ?></span>
											<div class="infobox-content">Desp. recetas</div>
										</div>

									</div>

									<div class="infobox infobox-red">
										<div class="infobox-icon">
											<i class="icon-group"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="colectivosPiezas"><?php echo number_format($colectivosPiezas, 0); ?></span>
											<div class="infobox-content">Desp. colectivos</div>
										</div>

									</div>

									<div class="infobox infobox-green">
										<div class="infobox-icon">
											<i class="icon-shopping-cart"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="paquetesPiezas"><?php echo number_format($paquetesPiezas, 0); ?></span>
											<div class="infobox-content">Desp. paquetes</div>
										</div>
									</div>

									<div class="space-6"></div>

								</div>
							</div>
						</div>
					</div>

                            <div class="row-fluid">
                                <div class="span6">
                                
                                	<div id="graficaRecetas" style="height: 500px; "></div>
                                
                                </div>
                                <div class="span6">
                                
                                	<div id="graficaDesplazamiento" style="height: 500px; "></div>
                                
                                </div>

                            </div>



                     <!-- Abasto y Dinero -->





					<div class="row-fluid">
						<div class="span12">

							<div class="row-fluid">
								<div class="span12 infobox-container">

									<div class="infobox infobox-blue">
										<div class="infobox-icon">
											<i class="icon-user"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="recetasTotal"><?php echo number_format($recetasTotal, 2); ?></span>
											<div class="infobox-content">Total Recetas</div>
										</div>

									</div>

									<div class="infobox infobox-red">
										<div class="infobox-icon">
											<i class="icon-group"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="colectivosTotal"><?php echo number_format($colectivosTotal, 2); ?></span>
											<div class="infobox-content">Total Colectivos</div>
										</div>

									</div>

									<div class="infobox infobox-green">
										<div class="infobox-icon">
											<i class="icon-shopping-cart"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="paquetesTotal"><?php echo number_format($paquetesTotal, 2); ?></span>
											<div class="infobox-content">Total Paquetes</div>
										</div>
									</div>


									<div class="infobox infobox-blue">
										<div class="infobox-icon">
											<i class="icon-user"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="recetasAbasto"><?php echo number_format($recetasAbasto, 2); ?></span>
											<div class="infobox-content">Abasto recetas</div>
										</div>

									</div>

									<div class="infobox infobox-red">
										<div class="infobox-icon">
											<i class="icon-group"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="colectivosAbasto"><?php echo number_format($colectivosAbasto, 2); ?></span>
											<div class="infobox-content">Abasto colectivos</div>
										</div>

									</div>

									<div class="infobox infobox-green">
										<div class="infobox-icon">
											<i class="icon-shopping-cart"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number" id="paquetesAbasto"><?php echo number_format($paquetesAbasto, 2); ?></span>
											<div class="infobox-content">Abasto paquetes</div>
										</div>
									</div>

									<div class="space-6"></div>

								</div>
							</div>
						</div>
					</div>

                            <div class="row-fluid">
                                <div class="span6">
                                
                                	<div id="graficaTotal" style="height: 500px; "></div>
                                
                                </div>
                                <div class="span6">
                                
                                	<div id="graficaAbasto" style="height: 500px; "></div>
                                
                                </div>
                            </div>
