<?php
# Scube/BaseBundle/Twig/Extension/BaseBundleExtension.php

namespace Scube\BaseBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;

class BaseBundleExtension extends \Twig_Extension
{
    protected $translator;

    public function __construct()
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'ago' => new \Twig_Filter_Method($this, 'ago')
        );
    }
    
    /**
     * Converts a datetime (outdated: from the past) to "ago" string
     * 
     * @param \Datetime() $datetime
     * @return string
     */
    public function ago (\DateTime $dateTime)
    {
        $delta = time() - $dateTime->getTimestamp();
        if ($delta < 0)
            throw new \InvalidArgumentException("Ago is unable to handle dates in the future");

        $duration = "";
		
		$unity = array(
						's'=>"second",
						'm'=>"minute",
						'h'=>"hour",
						'd'=>"day",
					   );
		
        if ($delta < 60)
        {
            // Seconds
            $time = $delta;
            $duration = $time . " ". $unity['s'] . (($time > 1) ? "s" : "");
        }
        else if ($delta <= 3600)
        {
            // Mins
            $time = floor($delta / 60);
            $duration = $time . " ". $unity['m'] . (($time > 1) ? "s" : "");
        }
        else if ($delta <= 86400)
        {
            // Hours
            $time = floor($delta / 3600);
            $duration = $time . " ". $unity['h'] . (($time > 1) ? "s" : "");
        }
        else
        {
            // Days
            $time = floor($delta / 86400);
            $duration = $time . " ". $unity['d'] . (($time > 1) ? "s" : "");
        }

        return $duration;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'base_bundle';
    }
}