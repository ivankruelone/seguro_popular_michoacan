							<div class="row-fluid">
                                <div class="span12">
                            
                                    <table id="ventas-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Sucursal</th>
                                                <th>Ticket</th>
                                                <th>Cliente</th>
                                                <th>Fecha</th>
                                                <th>Cajero</th>
                                                <th>Piezas</th>
                                                <th>IVA</th>
                                                <th>Importe</th>
                                                <th>Detalle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            $cantidad = 0;
                                            $iva = 0;
                                            $total = 0;
                                            
                                            foreach($query->result() as $row){
                                                
                                            ?>
                                            <tr>
                                                <td><?php echo $row->id; ?></td>
                                                <td><?php echo $row->nombresucursal; ?></td>
                                                <td><?php echo $row->venta; ?></td>
                                                <td><?php echo $row->razon; ?></td>
                                                <td><?php echo $row->fecha; ?></td>
                                                <td><?php echo $row->cajero; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->cantidad, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->iva, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->total, 2); ?></td>
                                                <td style="text-align: center;"><?php echo anchor('ventas/ventas_por_periodo__detalle_del_ticket/'.$row->id, '<i class="icon-medkit"></i>', array('class' => 'green')); ?></td>
                                            </tr>
                                            <?php 
                                            
                                                $cantidad = $cantidad + $row->cantidad;
                                                $iva = $iva + $row->iva;
                                                $total = $total + $row->total;
                                            
                                            } 
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" style="text-align: right;">Totales</td>
                                                <td style="text-align: right;"><?php echo number_format($cantidad, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($total, 2); ?></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    
                                    <a href="#modal-table" role="button" class="green" data-toggle="modal"> Table Inside a Modal Box </a>
							
                                    
                                    <div id="modal-table" class="modal hide fade" tabindex="-1">
								<div class="modal-header no-padding">
									<div class="table-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										Results for "Latest Registered Domains"
									</div>
								</div>

								<div class="modal-body no-padding">
									<div class="row-fluid">
										<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
											<thead>
												<tr>
													<th>Domain</th>
													<th>Price</th>
													<th>Clicks</th>

													<th>
														<i class="icon-time bigger-110"></i>
														Update
													</th>
												</tr>
											</thead>

											<tbody>
												<tr>
													<td>
														<a href="#">ace.com</a>
													</td>
													<td>$45</td>
													<td>3,330</td>
													<td>Feb 12</td>
												</tr>

												<tr>
													<td>
														<a href="#">base.com</a>
													</td>
													<td>$35</td>
													<td>2,595</td>
													<td>Feb 18</td>
												</tr>

												<tr>
													<td>
														<a href="#">max.com</a>
													</td>
													<td>$60</td>
													<td>4,400</td>
													<td>Mar 11</td>
												</tr>

												<tr>
													<td>
														<a href="#">best.com</a>
													</td>
													<td>$75</td>
													<td>6,500</td>
													<td>Apr 03</td>
												</tr>

												<tr>
													<td>
														<a href="#">pro.com</a>
													</td>
													<td>$55</td>
													<td>4,250</td>
													<td>Jan 21</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>

								<div class="modal-footer">
									<button class="btn btn-small btn-danger pull-left" data-dismiss="modal">
										<i class="icon-remove"></i>
										Close
									</button>

									<div class="pagination pull-right no-margin">
										<ul>
											<li class="prev disabled">
												<a href="#">
													<i class="icon-double-angle-left"></i>
												</a>
											</li>

											<li class="active">
												<a href="#">1</a>
											</li>

											<li>
												<a href="#">2</a>
											</li>

											<li>
												<a href="#">3</a>
											</li>

											<li class="next">
												<a href="#">
													<i class="icon-double-angle-right"></i>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
                            
                                </div>
                            </div>
