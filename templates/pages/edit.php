<div>
    <h4>Edycja notatki</h4>  
    <div>
    <?php if (!empty($params['note'])): ?>
    <?php $note = $params['note']; ?>
       <form action="/?action=edit" method="post" class="note-form">
        <input name="id" type="hidden" value="<?php echo $note['id']; ?>" />
            <ul>
                <li>
                    <label for="">Tytuł <span class="required">*</span></label>
                    <input type="text" name="title" class="field-long" value = "<?php echo $note['title'] ?>">
                </li>
                <li>
                    <label for="">Treść</label>
                    <textarea name="description" id="field5" class="field-long field-textarea"><?php echo $note['description'] ?></textarea>
                </li>
                <li>
                    <input type="submit" value="Submit">
                </li>
            </ul>
       </form>
       <?php else: ?>
            <div>
                <h4>Brak danych do wyświetlenia</h4>
                <a href="/"><button>Powrót do listy notatek</button></a>
            </div>
       <?php endif; ?>
    </div>
</div>