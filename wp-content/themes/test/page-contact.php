<?php
get_header(); ?>

<div class="content">
    <h1>Контакты</h1>
    <div class="contact-details">
        <p>Адрес: ул. Примерная, 123, г. Москва, Россия</p>
        <p>Телефон: +7 (123) 456-7890</p>
        <p>Email: info@example.com</p>
    </div>
    <div class="contact-form">
        <?php echo do_shortcode('[contact-form-7 id="1234" title="Контактная форма"]'); ?>
    </div>
</div>

<?php get_footer(); ?>
