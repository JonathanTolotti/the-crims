<?php

namespace App\Enums;

enum RefineFailureOutcomeEnum: string
{
    // O item não muda, apenas os materiais são perdidos.
    case MAINTAIN_TIER = 'maintain_tier';

    // O tier do item diminui em 1.
    case DOWNGRADE_TIER = 'downgrade_tier';

    // O item é permanentemente destruído.
    case DESTROY_ITEM = 'destroy_item';
}
