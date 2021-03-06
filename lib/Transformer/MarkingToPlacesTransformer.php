<?php

namespace JBJ\Workflow\SymfonyBridge\Transformer;

use Symfony\Component\Workflow\Marking;

/**
 * MarkingToPlacesTransformer
 *
 * Transforms a symfony/workflow::Marking <-> array of places
 */
class MarkingToPlacesTransformer
{
    /**
     * Transforms an object (Marking) to an array of places.
     *
     * @param  Marking|null $marking
     * @return array
     */
    public function transform($marking)
    {
        if (!$marking instanceof Marking) {
            return [];
        }
        $places = array_keys($marking->getPlaces());
        return $places;
    }

    /**
     * Transforms an array of places to an object (Marking).
     *
     * @param  array $places
     * @return Marking|null
     */
    public function reverseTransform($places)
    {
        $marking = new Marking();
        foreach ((array) $places as $place) {
            $marking->mark($place);
        }
        return $marking;
    }
}
