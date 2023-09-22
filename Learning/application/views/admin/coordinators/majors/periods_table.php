<table id="periodsTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Start date</th>
            <th>End date</th>
            <th>Status period</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (@$periods) {
            foreach (@$periods as $iPrd) { ?>
                <tr>
                    <td>
                        <?= @$iPrd->name_period; ?>
                    </td>
                    <td>
                        <?= @$iPrd->start_date_period; ?>
                    </td>
                    <td>
                        <?= @$iPrd->end_date_period; ?>
                    </td>
                    <td>
                        <?= @$iPrd->type_period; ?>
                    </td>
                </tr>
            <?php }
        } ?>
    </tbody>
</table>