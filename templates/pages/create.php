<div>
    <h4>Nowa notatka</h4>  
    <div>
    <?php if($params['created'] == true): ?>
    <div>
        <div>Tytuł: <?php echo $params['title'] ?></div>
        <div>Treść: <?php echo $params['description'] ?></div>
    </div>
    <?php else: ?>
       <form action="/?action=create" method="post" class="note-form">
            <ul>
                <li>
                    <label for="">Tytuł <span class="required">*</span></label>
                    <input type="text" name="title" class="field-long">
                </li>
                <li>
                    <label for="">Treść</label>
                    <textarea name="description" id="field5" class="field-long field-textarea"></textarea>
                </li>
                <li>
                    <input type="submit" value="Submit">
                </li>
            </ul>
       </form>
       <?php endif; ?>
    </div>
</div>