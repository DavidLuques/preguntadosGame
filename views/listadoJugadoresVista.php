<div class="container">
    <h1>Listado de Jugadores</h1>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Mail</th>
                    <th>País</th>
                    <th>Ciudad</th>
                    <th>Sexo</th>
                    <th>Nacimiento</th>
                    <th>Fecha Alta</th>
                    <th>Rol</th>
                    <th>Puntaje</th>
                    <th>Jugadas</th>
                    <th>Ganadas</th>
                    <th>Perdidas</th>
                    <th>Nivel</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jugadores)) : ?>
                    <?php foreach ($jugadores as $jugador) : ?>
                        <tr>
                            <td><?= $jugador['id'] ?></td>
                            <td>
                                <?php if (!empty($jugador['fotoPerfil'])) : ?>
                                    <img src="<?= $jugador['fotoPerfil'] ?>" alt="Foto de <?= htmlspecialchars($jugador['usuario']) ?>">
                                <?php else : ?>
                                    <img src="uploads/default.jpg" alt="Sin foto">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($jugador['usuario']) ?></td>
                            <td><?= htmlspecialchars($jugador['nombre']) ?></td>
                            <td><?= htmlspecialchars($jugador['apellido']) ?></td>
                            <td><?= htmlspecialchars($jugador['mail']) ?></td>
                            <td><?= htmlspecialchars($jugador['pais']) ?></td>
                            <td><?= htmlspecialchars($jugador['ciudad']) ?></td>
                            <td><?= htmlspecialchars($jugador['sexo']) ?></td>
                            <td><?= htmlspecialchars($jugador['nacimiento']) ?></td>
                            <td><?= htmlspecialchars($jugador['fechaAlta']) ?></td>
                            <td><?= htmlspecialchars($jugador['rol']) ?></td>
                            <td><?= htmlspecialchars($jugador['puntajeTotal']) ?></td>
                            <td><?= htmlspecialchars($jugador['partidasJugadas']) ?></td>
                            <td><?= htmlspecialchars($jugador['partidasGanadas']) ?></td>
                            <td><?= htmlspecialchars($jugador['partidasPerdidas']) ?></td>
                            <td><?= htmlspecialchars($jugador['nivelDificultad']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="17" class="text-center text-muted">No hay jugadores registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>