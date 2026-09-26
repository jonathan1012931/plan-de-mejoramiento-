<?php $flash = flashObtener(); ?>
<?php if ($flash): ?>
    <div class="<?= $flash['tipo'] === 'exito' ? 'login-message' : 'field-error' ?>" style="display:block;margin-bottom:var(--space-4)">
        <?= htmlspecialchars($flash['mensaje'], ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>