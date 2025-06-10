<?php

return [
    'exceptions' => [
        'configuration' => [
            'missing_merchant_code' => 'كود التاجر غير مكون.',
            'missing_secure_key' => 'المفتاح الآمن غير مكون.',
            'missing_base_url' => 'عنوان URL الأساسي غير مكون.',
        ],
        'payment' => [
            'invalid_items' => 'عناصر الدفع غير صالحة أو مفقودة.',
            'invalid_amount' => 'مبلغ الدفع غير صالح.',
            'invalid_customer_data' => 'بيانات العميل غير صالحة أو مفقودة.',
            'api_error' => 'خطأ في واجهة برمجة التطبيقات: :message',
            'required_field_missing' => "الحقل المطلوب :field مفقود",
            'required_user_field_missing' => "حقل المستخدم المطلوب :field مفقود أو فارغ",
            'invalid_redirect_url' => 'تنسيق عنوان URL غير صالح',
            'invalid_webhook_url' => 'تنسيق عنوان webhook غير صالح',
            'items' => [
                'required' => 'مطلوب عنصر واحد على الأقل',
                'invalid' => 'يجب أن يحتوي العنصر على معرف وسعر صالحين',
                'invalid_quantity' => 'يجب أن تكون كمية العنصر رقماً موجباً',
            ],
        ],
    ],
]; 