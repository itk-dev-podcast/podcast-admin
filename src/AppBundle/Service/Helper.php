<?php

namespace AppBundle\Service;

class Helper
{
    public function getDuration(string $spec)
    {
        // @see https://help.apple.com/itc/podcasts_connect/#/itcb54353390
        if (is_numeric($spec)) {
            return (int) $spec;
        }

        if (preg_match('/(?:(?<hours>[0-9]+):)?(?<minutes>[0-9]+):(?<seconds>[0-9]+)/', $spec, $matches)) {
            $duration = 0;
            if (isset($matches['hours'])) {
                $duration += 60 * 60 * (int) $matches['hours'];
            }
            if (isset($matches['minutes'])) {
                $duration += 60 * (int) $matches['minutes'];
            }
            if (isset($matches['seconds'])) {
                $duration += (int) $matches['seconds'];
            }

            return $duration;
        }

        return null;
    }
}
