<label for="exampleInputBorderWidth2">Group:</label>
<select class="form-control form-control-border" name="inpGroup" id="inpGroup">
    <option value="" hidden>Select one</option>
    <?php if (@$groups) {
        foreach ($groups as $grp) { ?>
            <option value="<?= $grp->id_group ?>">
                <?= $grp->clave_group ?>
            </option>
        <?php }
    } ?>
</select>