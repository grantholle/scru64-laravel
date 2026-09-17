<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Node Spec
    |--------------------------------------------------------------------------
    |
    | "<node_id>/<node_id_size>", e.g. "42/8". The default is fine for a
    | single server. If more than one server generates IDs, each one must
    | have a unique node ID or IDs are not guaranteed unique.
    |
    */

    'node_spec' => env('SCRU64_NODE_SPEC', '1/8'),

];
