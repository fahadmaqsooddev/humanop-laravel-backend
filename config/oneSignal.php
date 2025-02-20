<?php

if (env('APP_ENV') == 'local') {
    return [
        'auth_key'=>'os_v2_app_5ghm3f2cbzcrjfyo4dvxnww24gpeqtodqpnunxniznfd5flt73fb2pzznehmxlwdvhzsqrjmd3w3f23dtmvmzuutgxh62m5uw7hrvza',
        'app_id'=>'e98ecd97-420e-4514-970e-e0eb76dadae1',
        
    ];
} elseif (env('APP_ENV') == 'development') {
    return [
     
        'auth_key'=>'os_v2_app_5ghm3f2cbzcrjfyo4dvxnww24gpeqtodqpnunxniznfd5flt73fb2pzznehmxlwdvhzsqrjmd3w3f23dtmvmzuutgxh62m5uw7hrvza',
        'app_id'=>'e98ecd97-420e-4514-970e-e0eb76dadae1',
    ];
} elseif (env('APP_ENV') == 'staging') {
    return [
        'auth_key'=>'os_v2_app_apqui2sgingzhhmwqi6pd74nes5prhwtspuuktupl5fxwewqtdcmiimtvf7hcidvmcnhg4pnuikpgd5s7vt2eaydpbdxw6fjsphexma',
        'app_id'=>'03e1446a-4643-4d93-9d96-823cf1ff8d24',
    ];
} elseif (env('APP_ENV') == 'production') {
    return [
        'auth_key'=>'os_v2_app_v54i2yegj5fgtbusk2ceblhk3bdwjqk5yxaev7vmg7jxfmp25mto7u3guprt434briffc5b5x7dbmgezoub4z5oxp4pkfwuwb4rle3i',
        'app_id'=>'af788d60-864f-4a69-8692-568440acead8',
    ];
}






