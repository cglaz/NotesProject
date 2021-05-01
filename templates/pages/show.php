<div class="show">
<?php $note = $params['note'] ?? null; ?>
<?php if($note) : ?>
    <ul>
        <li>Id: <?php echo $note['id'] ?></li>        
        <li>Tytuł: <?php echo $note['title'] ?></li>        
        <li>Opis: <?php echo $note['description'] ?></li>        
        <li>Data utworzenia: <?php echo $note['created'] ?></li>        
    </ul>
    <a href="/?action=edit&id=<?php echo $note['id'] ?>"><button>Edytuj</button></a>
<?php else : ?>
<div>
    <h1>Brak notatki do wyświetlenia!</h1>
</div>
<?php endif; ?>
    <a href="/">
        <button>Powrót do listy notatek</button>
    </a>
</div>