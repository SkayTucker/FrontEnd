<section class="vehicles">
    <div class="vehicles-content">
        <!-- Opções -->
        <fieldset class="vehicles-options">
            <legend>Opções</legend>
            <button class="open-modal" data-modal-template-url="cadastrarVeiculo.php">➕ Novo Veículo</button>
        </fieldset>

        <!-- Seção principal -->
        <div class="vehicle-box">
            <fieldset class="vehicles-section">
                <legend>
                    <h3>Veículos</h3>
                </legend>

                <!-- Veículos disponíveis -->
                <?php
                function renderVehicleCard($img, $infoHtml, $plate)
                {
                    $url = 'historicoVeiculo.php?plate=' . urlencode($plate);
                    return <<<HTML
                        <div class="vehicle-cards">
                            <div class="menu-vehicle-info">
                                <img src="{$img}" alt="Veículo" class="vehicle-img">
                                <div class="vehicle-info-text">
                                    {$infoHtml}
                                </div>
                            <div class="open-modal" data-modal-template-url="{$url}">Histórico</div>
                            </div>
                        </div>
                        HTML;
                }

                echo '<fieldset>';
                echo '<legend><h3>Disponíveis</h3></legend>';

                try {
                    $sql = "SELECT plate, brand, model, photos_dir FROM vehicles WHERE availability = 'disponível'";
                    $stmt = $pdo->query($sql);
                    $veiculosDisponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($veiculosDisponiveis) {
                        foreach ($veiculosDisponiveis as $v) {
                            $img = htmlspecialchars(IMAGES_URL . ($v['photos_dir'] ?? '/corolla.jpg'));
                            $info = "<strong>" . htmlspecialchars("{$v['brand']} {$v['model']} ({$v['plate']})") . "</strong>";
                            echo renderVehicleCard($img, $info, $v['plate']);
                        }
                    } else {
                        echo "<p>Nenhum veículo disponível.</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p>Erro ao buscar veículos disponíveis.</p>";
                }

                echo '</fieldset>';

                // Veículos em uso
                echo '<fieldset>';
                echo '<legend><h3>Veículos em Uso</h3></legend>';

                try {
                    $sql = "SELECT vc.vehicle_plate, v.brand, v.model, v.photos_dir, u.name AS usuario, vc.created_at 
                                FROM vehicle_checkin vc
                                INNER JOIN vehicles v ON vc.vehicle_plate = v.plate
                                INNER JOIN users u ON vc.user_id = u.id
                                WHERE vc.status = 'em uso'";
                    $stmt = $pdo->query($sql);
                    $veiculosUso = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($veiculosUso) {
                        foreach ($veiculosUso as $v) {
                            $img = htmlspecialchars(IMAGES_URL . ($v['photos_dir'] ?? '/corolla.jpg'));
                            $info = "<strong>" . htmlspecialchars("{$v['brand']} {$v['model']} ({$v['vehicle_plate']})") . "</strong><br>"
                                . "Usuário: " . htmlspecialchars($v['usuario']) . "<br>"
                                . "Desde: " . date('d/m/Y H:i', strtotime($v['created_at']));
                            echo renderVehicleCard($img, $info, $v['vehicle_plate']);
                        }
                    } else {
                        echo "<p>Nenhum veículo em uso.</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p>Erro ao buscar veículos em uso.</p>";
                }

                echo '</fieldset>';
                ?>
            </fieldset>
        </div>
    </div>
</section>