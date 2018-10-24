<?php

namespace Gamboa\AdminBundle\Helper;

class Format
{
    const RUT_FORMATTED   = 1; // '/^\d{1,2}\.\d{3}\.\d{3}[-][0-9kK]{1}$/';
    const RUT_NUMBER_ONLY = 2; // '/^[0-9]+$/';
    const RUT_DV_ONLY     = 3; // '/^[0-9Kk]+$/';
    const RUT_NO_DOTS     = 4; // '/^\d{1,2}\d{3}\d{3}[-][0-9kK]{1}$/';
}