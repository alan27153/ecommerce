<section class="profile-container">
  <h2>Mi Perfil</h2>

  <?php if (!empty($user)): ?>
    <div class="profile-card">
      <div class="profile-info">
        <p><strong>Nombre:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Rol:</strong> <?= ucfirst($user['role']) ?></p>
        <p><strong>Fecha de registro:</strong> <?= date("d/m/Y", strtotime($user['created_at'])) ?></p>
      </div>

      <div class="profile-extra">
        <p><strong>Dirección:</strong> <?= htmlspecialchars($user['address'] ?? 'No registrada') ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($user['phone'] ?? 'No registrado') ?></p>
        <p><strong>Documento:</strong> <?= htmlspecialchars($user['document_number'] ?? 'No registrado') ?></p>
      </div>

      <div class="profile-actions">
        <a href="/ecommerce/orders" class="btn"><i class="fa-solid fa-box"></i> Mis Pedidos</a>
        <a href="/ecommerce/logout" class="btn logout"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
      </div>
    </div>
  <?php else: ?>
    <p>No se pudo cargar la información del perfil.</p>
  <?php endif; ?>
</section>

<style>
.profile-container {
  padding: 30px;
  max-width: 600px;
  margin: 0 auto;
}
.profile-card {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.profile-info p, .profile-extra p {
  margin: 8px 0;
  font-size: 15px;
}
.profile-actions {
  margin-top: 20px;
  display: flex;
  gap: 10px;
  justify-content: center;
}
.btn {
  background-color: #007bff;
  color: #fff;
  padding: 8px 14px;
  border-radius: 8px;
  text-decoration: none;
  transition: 0.2s;
}
.btn:hover {
  background-color: #0056b3;
}
.btn.logout {
  background-color: #dc3545;
}
.btn.logout:hover {
  background-color: #b02a37;
}
</style>
