<?php
namespace App\Enums;

enum FormulaKalori: string {
    case HARRIS_BENEDICT = 'harris_benedict';
    case MIFFLIN_ST_JEOR = 'mifflin_st_jeor';
    case WHO = 'who';
    case KONSENSUS_DM = 'konsensus_dm';
    case KONSENSUS_CKD = 'konsensus_ckd';
}
