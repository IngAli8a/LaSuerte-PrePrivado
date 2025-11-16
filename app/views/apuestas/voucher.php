 <div id="voucher-print" class="ticket-wrapper">
            <div class="ticket-entrada">
                <!-- Sección Izquierda (Stub) -->
                <div class="ticket-stub">
                    <div class="stub-number">
                        <?php 
                        // Extraer últimos 8 dígitos del código
                        $codigo_corto = substr($apuesta['codigo_voucher'], -8);
                        echo "N° " . $codigo_corto;
                        ?>
                    </div>
                    <div class="stub-title">Ticket</div>
                    <div class="stub-sections">
                        <div class="stub-section">
                            <div class="stub-label">SORTEO</div>
                            <div class="stub-value"><?php echo str_pad($apuesta['numero_sorteo_dia'], 2, '0', STR_PAD_LEFT); ?></div>
                        </div>
                        <div class="stub-section">
                            <div class="stub-label">NÚMERO</div>
                            <div class="stub-value"><?php echo str_pad($apuesta['numero_apostado'], 2, '0', STR_PAD_LEFT); ?></div>
                        </div>
                        <div class="stub-section">
                            <div class="stub-label">MONTO</div>
                            <div class="stub-value">Q<?php echo number_format($apuesta['monto_apostado'], 0); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Línea de Perforación -->
                <div class="ticket-perforation">
                    <div class="perf-circle"></div>
                    <div class="perf-circle"></div>
                    <div class="perf-circle"></div>
                    <div class="perf-circle"></div>
                    <div class="perf-circle"></div>
                    <div class="perf-circle"></div>
                    <div class="perf-circle"></div>
                    <div class="perf-circle"></div>
                    <div class="perf-circle"></div>
                    <div class="perf-circle"></div>
                    <div class="perf-circle"></div>
                    <div class="perf-circle"></div>
                </div>

                <!-- Sección Principal -->
                <div class="ticket-main">
                    <!-- Header -->
                    <div class="main-header">
                        <div class="header-title">LOTERÍA TICKET</div>
                        <div class="header-code"><?php echo e($apuesta['codigo_voucher']); ?></div>
                    </div>

                    <!-- Participantes (Cliente vs Sorteo) -->
                    <div class="participants">
                        <div class="participant left">
                            <div class="participant-label">Cliente</div>
                            <div class="participant-name"><?php echo strtoupper(e($apuesta['nombre'] . ' ' . $apuesta['apellido'])); ?></div>
                        </div>
                        
                        <div class="vs-badge">VS</div>
                        
                        <div class="participant right">
                            <div class="participant-label">Sorteo</div>
                            <div class="participant-name"><?php echo strtoupper(e($apuesta['tipo_sorteo'])); ?></div>
                        </div>
                    </div>

                    <!-- Referee (Número Apostado) -->
                    <div class="referee-section">
                        <div class="referee-label">NÚMERO APOSTADO</div>
                        <div class="referee-name"><?php echo str_pad($apuesta['numero_apostado'], 2, '0', STR_PAD_LEFT); ?></div>
                    </div>

                    <!-- Fecha y Hora -->
                    <div class="event-datetime">
                        <?php 
                        $fecha = date('F d, g:iA', strtotime($apuesta['fecha_hora_apuesta']));
                        $meses = ['January'=>'ENERO', 'February'=>'FEBRERO', 'March'=>'MARZO', 'April'=>'ABRIL', 
                                  'May'=>'MAYO', 'June'=>'JUNIO', 'July'=>'JULIO', 'August'=>'AGOSTO',
                                  'September'=>'SEPTIEMBRE', 'October'=>'OCTUBRE', 'November'=>'NOVIEMBRE', 'December'=>'DICIEMBRE'];
                        foreach($meses as $en => $es) {
                            $fecha = str_replace($en, $es, $fecha);
                        }
                        echo strtoupper($fecha);
                        ?>
                    </div>

                    <!-- Premio -->
                    <div class="prize-section">
                        <div class="prize-label">PREMIO POTENCIAL</div>
                        <div class="prize-amount">Q <?php echo number_format($apuesta['premio_potencial'], 2); ?></div>
                        <?php if ($apuesta['es_cumpleanos']): ?>
                        <div class="prize-bonus"> +10% BONO CUMPLEAÑOS</div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer -->
                    <div class="main-footer">
                        <div class="footer-notice">Este ticket es válido únicamente para el sorteo indicado</div>

                    </div>
                </div>
            </div>

            <!-- Información Adicional (Debajo del ticket) -->
            <div class="ticket-info-extra no-print">
                <div class="info-item">
                    <span class="info-icon"></span>
                    <span class="info-text">DPI: <?php echo e($apuesta['dpi']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-icon"></span>
                    <span class="info-text">Sorteo: <?php echo date('d/m/Y', strtotime($apuesta['fecha_sorteo'])); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-icon"></span>
                    <span class="info-text">Vendedor: <?php echo e($apuesta['vendedor']); ?></span>
                </div>
            </div>
        </div>