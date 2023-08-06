<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $setting = $this->findSetting('APP_URL');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'http://localhost',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('CLIENT_URL');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'https://ncms-staging-website.azurewebsites.net',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('APP_NAME');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'Legal System',
                'group' => 'app',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('APP_LOGO');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'http://localhost:8080/images/logo.png',
                'group' => 'app',
                'type' => 'file',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('APP_LOGO_SM');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'http://localhost:8080/images/logo.png',
                'group' => 'app',
                'type' => 'file',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('APP_KEY');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'base64:OYPpqSn1YsClsk/8dkpJhuB2PIE5T34RzR3d0a19nwM=',
                'group' => 'app',
                'type' => 'textarea',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('LOG_CHANNEL');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'stack',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('LOG_CHANNEL');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'stack',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        if (env('APP_ENV') == 'production') {
            $setting = $this->findSetting('APP_ENV');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'production',
                    'group' => 'app',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('APP_DEBUG');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'false',
                    'group' => 'app',
                    'type' => 'boolean',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_CONNECTION');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'mysql',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_HOST');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'localhost',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PORT');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '3306',
                    'group' => 'database',
                    'type' => 'number',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_DATABASE');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'lds_new',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => true,
                ])->save();
            }
            $setting = $this->findSetting('DB_USERNAME');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'root',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PASSWORD');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '@$-NCMS@$-123',
                    'group' => 'database',
                    'type' => 'password',
                    'editable' => true,
                ])->save();
            }
        } elseif (env('APP_ENV') == 'staging') {
            $setting = $this->findSetting('APP_ENV');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'staging',
                    'group' => 'app',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('APP_DEBUG');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'true',
                    'group' => 'app',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_CONNECTION');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'mysql',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_HOST');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'lds',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PORT');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '3306',
                    'group' => 'database',
                    'type' => 'number',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_DATABASE');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'lds_new',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_USERNAME');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'root',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PASSWORD');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '@$-NCMS@$-123',
                    'group' => 'database',
                    'type' => 'number',
                    'editable' => false,
                ])->save();
            }
        } else {
            $setting = $this->findSetting('APP_ENV');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'local',
                    'group' => 'app',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('APP_DEBUG');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'true',
                    'group' => 'app',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_CONNECTION');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'mysql',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_HOST');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '127.0.0.1',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PORT');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '3306',
                    'group' => 'database',
                    'type' => 'number',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_DATABASE');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'lds_new',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_USERNAME');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => 'root',
                    'group' => 'database',
                    'type' => 'text',
                    'editable' => false,
                ])->save();
            }
            $setting = $this->findSetting('DB_PASSWORD');
            if (!$setting->exists) {
                $setting->fill([
                    'isEnv' => true,
                    'value' => '@$-NCMS@$-123',
                    'group' => 'database',
                    'type' => 'password',
                    'editable' => false,
                ])->save();
            }
        }
        $setting = $this->findSetting('BROADCAST_DRIVER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'log',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('CACHE_DRIVER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'file',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('QUEUE_CONNECTION');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'database',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('SESSION_DRIVER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'file',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('SESSION_LIFETIME');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '120',
                'group' => 'app',
                'type' => 'number',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('REDIS_HOST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '127.0.0.1',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('REDIS_PASSWORD');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'group' => 'app',
                'type' => 'password',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('REDIS_PORT');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '6379',
                'group' => 'app',
                'type' => 'number',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_DRIVER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'smtp.ncms.sa',
                'group' => 'mail',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_Link');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => 'http://192.168.150.44',
                'group' => 'mail',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_HOST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'smtp.ncms.sa',
                'group' => 'mail',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_PORT');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '25',
                'group' => 'mail',
                'type' => 'number',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_USERNAME');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'mail',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_PASSWORD');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'mail',
                'type' => 'password',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_ENCRYPTION');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'mail',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('LDAP_LOGGING');
        if (!$setting->exists) {
            $setting->fill([
                'value' => 'true',
                'group' => 'ldap',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('LDAP_CONNECTION');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'default',
                'group' => 'ldap',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('LDAP_HOST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'dc01.ncms.local',
                'group' => 'ldap',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('LDAP_USERNAME');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'CN=JASSVC,OU=Service Accounts,DC=ncms,DC=local',
                'group' => 'ldap',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('LDAP_PASSWORD');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'P@ssw0rd@wakeb',
                'group' => 'ldap',
                'type' => 'password',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('LDAP_PORT');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '389',
                'group' => 'ldap',
                'type' => 'number',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('LDAP_BASE_DN');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'Dc=ncms,Dc=local',
                'group' => 'ldap',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('LDAP_TIMEOUT');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '5',
                'group' => 'ldap',
                'type' => 'number',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('LDAP_SSL');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'true',
                'group' => 'ldap',
                'type' => 'boolean',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('LDAP_TLS');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'true',
                'group' => 'ldap',
                'type' => 'boolean',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('AWS_ACCESS_KEY_ID');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('AWS_SECRET_ACCESS_KEY');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('AWS_DEFAULT_REGION');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'us-east-1',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('AWS_BUCKET');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('PUSHER_APP_ID');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('PUSHER_APP_KEY');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('PUSHER_APP_SECRET');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('PUSHER_APP_CLUSTER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'mt1',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('MIX_PUSHER_APP_KEY');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '${PUSHER_APP_KEY}',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('MIX_PUSHER_APP_CLUSTER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '${PUSHER_APP_CLUSTER}',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('QUEUE_DRIVER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => 'database',
                'group' => 'app',
                'type' => 'text',
                'editable' => false,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_NOTIFY_NEW_USER');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '<h3 class="ql-align-right">نرحب بك في النظام الالكتروني للخدمات القانونية للشركة الوطنية للانظمة الميكانيكة</h3><p class="ql-align-right">ونفيدكم بانه تم انشاء مستخدم وبياناته كالاتي:</p><p class="ql-align-right"><br></p><p class="ql-align-right">اسم المستخدم: [[name]]</p><p class="ql-align-right">الايميل: [[email]]</p><p class="ql-align-right">كلمة المرور: [[password]]</p><p class="ql-align-right"><br></p><p class="ql-align-right">يمكنكم تغير كلمة المرور المنشئة من النظام بشكل تلقائي بعد تسجيل الدخول للنظام لاول مره,وتحملكم لكامل المسؤولية القانونية للمحافظة على معلوماتك السرية.</p><p class="ql-align-right"><br></p><h6><span style="color: red;">تحذير:</span><span style="color: black;"> المعلومات الواردة في هذا البريد الإلكتروني وفي أي ملفات تم إرسالها معه موجهة فقط إلى المرسل إليه وقد تحتوي على مواد سرية و / أو مميزة. يرجى العلم بأن أي إفشاء أو نسخ أو توزيع أو استخدام محتويات هذه المعلومات محظور. الوصول إلى هذا البريد الإلكتروني من قبل أي شخص آخر غير المصرح به. إذا كنت قد تلقيت هذا البريد الإلكتروني عن طريق الخطأ ، يرجى حذف البريد الإلكتروني وتدمير أي نسخ منه. إذا لم تكن المستلم المقصود ، فسيُحظر تمامًا أي إفشاء أو نسخ أو توزيع أو أي إجراء تم اتخاذه أو حذفه ليتم الاعتماد عليه. على الرغم من أنه تم فحص هذا البريد الإلكتروني بحثًا عن احتمال وجود فيروسات الكمبيوتر قبل الإرسال ، يجب على المستلم التحقق من هذا البريد الإلكتروني وأي مرفقات بحثًا عن وجود فيروسات. لا تقبل الشركة الوطنية للأنظمة الميكانيكية أي مسؤولية عن أي ضرر ناتج عن أي فيروس ينتقل عن طريق مجال البريد الإلكتروني هذا. البيان والآراء الواردة في هذا البريد الإلكتروني هي آراء المرسل ولا تعكس بالضرورة آراء الشركة الوطنية للأنظمة الميكانيكية</span></h6><h6><span style="color: red;">Warning:</span><span style="color: rgb(127, 127, 127);"> </span><span style="color: black;">The information in this email and in any files transmitted with it is intended only for the addressee and may contain &nbsp;&nbsp;confidential and / or privileged material. Please be aware that any disclosure, copying, distribution or use of the contents of this information is prohibited. Access to this email by anyone else is unauthorized. If you have received this email in error please delete the e-mail and destroy any copies of it. If you are not the intended recipient, any disclosure, copying, distribution or any action taken or omitted to be taken in reliance on it, is strictly prohibited. Although this email has been scanned for the possible presence of computer viruses prior to dispatch, the recipient should check this email and any attachments for the presence of viruses. NCMS accepts no liability for any damage caused by any virus transmitted </span>	<span style="color: black;">by this email domain. Statement and opinions expressed in this e-mail are those of the sender, and not necessarily reflect those of NCMS</span></h6>',
                'group' => 'mail',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_NOTIFY_CHANGE_PASSWORD');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '<h3 class="ql-align-right">نرحب بك في النظام الالكتروني للخدمات القانونية للشركة الوطنية للانظمة الميكانيكة</h3><p class="ql-align-right">ونفيدكم بانه تم تغير كلمة المرور والبيانات كالاتي:</p><p class="ql-align-right"><br></p><p class="ql-align-right">اسم المستخدم: [[name]]</p><p class="ql-align-right">الايميل: [[email]]</p><p class="ql-align-right">كلمة المرور: [[password]]</p><p class="ql-align-right"><br></p><p class="ql-align-right">ونفيدكم بتحملكم لكامل المسؤولية القانونية للمحافظة على معلوماتك السرية.</p><p class="ql-align-right"><br></p><h6><span style="color: red;">تحذير:</span><span style="color: black;"> المعلومات الواردة في هذا البريد الإلكتروني وفي أي ملفات تم إرسالها معه موجهة فقط إلى المرسل إليه وقد تحتوي على مواد سرية و / أو مميزة. يرجى العلم بأن أي إفشاء أو نسخ أو توزيع أو استخدام محتويات هذه المعلومات محظور. الوصول إلى هذا البريد الإلكتروني من قبل أي شخص آخر غير المصرح به. إذا كنت قد تلقيت هذا البريد الإلكتروني عن طريق الخطأ ، يرجى حذف البريد الإلكتروني وتدمير أي نسخ منه. إذا لم تكن المستلم المقصود ، فسيُحظر تمامًا أي إفشاء أو نسخ أو توزيع أو أي إجراء تم اتخاذه أو حذفه ليتم الاعتماد عليه. على الرغم من أنه تم فحص هذا البريد الإلكتروني بحثًا عن احتمال وجود فيروسات الكمبيوتر قبل الإرسال ، يجب على المستلم التحقق من هذا البريد الإلكتروني وأي مرفقات بحثًا عن وجود فيروسات. لا تقبل الشركة الوطنية للأنظمة الميكانيكية أي مسؤولية عن أي ضرر ناتج عن أي فيروس ينتقل عن طريق مجال البريد الإلكتروني هذا. البيان والآراء الواردة في هذا البريد الإلكتروني هي آراء المرسل ولا تعكس بالضرورة آراء الشركة الوطنية للأنظمة الميكانيكية</span></h6><h6><span style="color: red;">Warning:</span><span style="color: rgb(127, 127, 127);"> </span><span style="color: black;">The information in this email and in any files transmitted with it is intended only for the addressee and may contain &nbsp;&nbsp;confidential and / or privileged material. Please be aware that any disclosure, copying, distribution or use of the contents of this information is prohibited. Access to this email by anyone else is unauthorized. If you have received this email in error please delete the e-mail and destroy any copies of it. If you are not the intended recipient, any disclosure, copying, distribution or any action taken or omitted to be taken in reliance on it, is strictly prohibited. Although this email has been scanned for the possible presence of computer viruses prior to dispatch, the recipient should check this email and any attachments for the presence of viruses. NCMS accepts no liability for any damage caused by any virus transmitted by this email domain. Statement and opinions expressed in this e-mail are those of the sender, and not necessarily reflect those of NCMS</span></h6><p class="ql-align-right"><br></p>',
                'group' => 'mail',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_NOTIFY_NEW_REQUEST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '<h3 class="ql-align-right">نرحب بك في النظام الالكتروني للخدمات القانونية للشركة الوطنية للانظمة الميكانيكة</h3><p class="ql-align-right">نفيدكم بانه تم رفع طلب جديد برقم: [[requestNo]]  من المستفيد: [[name]] وحالة الطلب بانتظار التعيين</p><p class="ql-align-right">بيانات الطلب كالاتي:</p><p class="ql-align-right"><br></p><p class="ql-align-right">اسم مقدم الطلب:[[name]]</p><p class="ql-align-right">الايميل: [[email]]</p><p class="ql-align-right">رقم الطلب: &nbsp;[[requestNo]]</p><p class="ql-align-right">رابط الطلب: [[mailLink]]</p><p class="ql-align-right"><br></p><p class="ql-align-right">يمكنكم تعيين الطلب للدخول من هنا</p><p class="ql-align-right"><br></p><h6 class="ql-align-right"><span style="color: red;">تحذير:</span><span style="color: black;"> المعلومات الواردة في هذا البريد الإلكتروني وفي أي ملفات تم إرسالها معه موجهة فقط إلى المرسل إليه وقد تحتوي على مواد سرية و / أو مميزة. يرجى العلم بأن أي إفشاء أو نسخ أو توزيع أو استخدام محتويات هذه المعلومات محظور. الوصول إلى هذا البريد الإلكتروني من قبل أي شخص آخر غير المصرح به. إذا كنت قد تلقيت هذا البريد الإلكتروني عن طريق الخطأ ، يرجى حذف البريد الإلكتروني وتدمير أي نسخ منه. إذا لم تكن المستلم المقصود ، فسيُحظر تمامًا أي إفشاء أو نسخ أو توزيع أو أي إجراء تم اتخاذه أو حذفه ليتم الاعتماد عليه. على الرغم من أنه تم فحص هذا البريد الإلكتروني بحثًا عن احتمال وجود فيروسات الكمبيوتر قبل الإرسال ، يجب على المستلم التحقق من هذا البريد الإلكتروني وأي مرفقات بحثًا عن وجود فيروسات. لا تقبل الشركة الوطنية للأنظمة الميكانيكية أي مسؤولية عن أي ضرر ناتج عن أي فيروس ينتقل عن طريق مجال البريد الإلكتروني هذا. البيان والآراء الواردة في هذا البريد الإلكتروني هي آراء المرسل ولا تعكس بالضرورة آراء الشركة الوطنية للأنظمة الميكانيكية</span></h6><h6><span style="color: red;">Warning:</span><span style="color: rgb(127, 127, 127);"> </span><span style="color: black;">The information in this email and in any files transmitted with it is intended only for the addressee and may contain &nbsp;&nbsp;confidential and / or privileged material. Please be aware that any disclosure, copying, distribution or use of the contents of this information is prohibited. Access to this email by anyone else is unauthorized. If you have received this email in error please delete the e-mail and destroy any copies of it. If you are not the intended recipient, any disclosure, copying, distribution or any action taken or omitted to be taken in reliance on it, is strictly prohibited. Although this email has been scanned for the possible presence of computer viruses prior to dispatch, the recipient should check this email and any attachments for the presence of viruses. NCMS accepts no liability for any damage caused by any virus transmitted by this email domain. Statement and opinions expressed in this e-mail are those of the sender, and not necessarily reflect those of NCMS</span></h6><p><br></p>',
                'group' => 'mail',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_NOTIFY_EDIT_REQUEST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '<h3 class="ql-align-right">نرحب بك في النظام الالكتروني للخدمات القانونية للشركة الوطنية للانظمة الميكانيكة</h3><p class="ql-align-right">نفيدكم بانه تم اعادة الطلب [[requestNo]] للتعديل وفق السبب الموضح لكم في الطلب </p><p class="ql-align-right">نأمل منكم التعديل على الطلب وفق المطلوب لنتمكن من خدمتكم.</p><p class="ql-align-right"><br></p><p class="ql-align-right"><strong>User name:</strong>&nbsp;[[name]]&nbsp;</p><p class="ql-align-right"><strong>User email:</strong>&nbsp;[[email]]</p><p class="ql-align-right"><strong>Request number:</strong>&nbsp;[[requestNo]]</p><p class="ql-align-right"><strong>Request URL: [[mailLink]]</strong></p><p><br></p><p><br></p><h6><span style="color: red;">تحذير:</span><span style="color: black;"> المعلومات الواردة في هذا البريد الإلكتروني وفي أي ملفات تم إرسالها معه موجهة فقط إلى المرسل إليه وقد تحتوي على مواد سرية و / أو مميزة. يرجى العلم بأن أي إفشاء أو نسخ أو توزيع أو استخدام محتويات هذه المعلومات محظور. الوصول إلى هذا البريد الإلكتروني من قبل أي شخص آخر غير المصرح به. إذا كنت قد تلقيت هذا البريد الإلكتروني عن طريق الخطأ ، يرجى حذف البريد الإلكتروني وتدمير أي نسخ منه. إذا لم تكن المستلم المقصود ، فسيُحظر تمامًا أي إفشاء أو نسخ أو توزيع أو أي إجراء تم اتخاذه أو حذفه ليتم الاعتماد عليه. على الرغم من أنه تم فحص هذا البريد الإلكتروني بحثًا عن احتمال وجود فيروسات الكمبيوتر قبل الإرسال ، يجب على المستلم التحقق من هذا البريد الإلكتروني وأي مرفقات بحثًا عن وجود فيروسات. لا تقبل الشركة الوطنية للأنظمة الميكانيكية أي مسؤولية عن أي ضرر ناتج عن أي فيروس ينتقل عن طريق مجال البريد الإلكتروني هذا. البيان والآراء الواردة في هذا البريد الإلكتروني هي آراء المرسل ولا تعكس بالضرورة آراء الشركة الوطنية للأنظمة الميكانيكية</span></h6><h6><span style="color: red;">Warning:</span><span style="color: rgb(127, 127, 127);"> </span><span style="color: black;">The information in this email and in any files transmitted with it is intended only for the addressee and may contain &nbsp;&nbsp;confidential and / or privileged material. Please be aware that any disclosure, copying, distribution or use of the contents of this information is prohibited. Access to this email by anyone else is unauthorized. If you have received this email in error please delete the e-mail and destroy any copies of it. If you are not the intended recipient, any disclosure, copying, distribution or any action taken or omitted to be taken in reliance on it, is strictly prohibited. Although this email has been scanned for the possible presence of computer viruses prior to dispatch, the recipient should check this email and any attachments for the presence of viruses. NCMS accepts no liability for any damage caused by any virus transmitted by this email domain. Statement and opinions expressed in this e-mail are those of the sender, and not necessarily reflect those of NCMS</span></h6><p><br></p>',
                'group' => 'mail',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_NOTIFY_ASSIGNED_REQUEST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '<h3 class="ql-align-right">نرحب بك في النظام الالكتروني للخدمات القانونية للشركة الوطنية للانظمة الميكانيكة</h3><p class="ql-align-right">نفيدكم بانه تم تعيين الطلب [[requestNo]] لمعالجته وفق الإجراءات المتبعة.</p><p class="ql-align-right">للإحاطة والعلم بأن طلبكم تحت الدراسة والإجراءات القانوني،،</p><p class="ql-align-right"><br></p><p class="ql-align-right">نفيدكم بانه تم تعيين طلب جديد لكم [[requestNo]] </p><p class="ql-align-right">بيانات الطلب كالاتي:</p><p class="ql-align-right">اسم مقدم الطلب:[[name]]</p><p class="ql-align-right">الايميل: [[email]]</p><p class="ql-align-right">رقم الطلب: &nbsp;[[requestNo]]</p><p class="ql-align-right">عنوان الطلب: [[mailLink]]</p><p class="ql-align-right"><br></p><p class="ql-align-right"><br></p><h6><span style="color: red;">تحذير:</span><span style="color: black;"> المعلومات الواردة في هذا البريد الإلكتروني وفي أي ملفات تم إرسالها معه موجهة فقط إلى المرسل إليه وقد تحتوي على مواد سرية و / أو مميزة. يرجى العلم بأن أي إفشاء أو نسخ أو توزيع أو استخدام محتويات هذه المعلومات محظور. الوصول إلى هذا البريد الإلكتروني من قبل أي شخص آخر غير المصرح به. إذا كنت قد تلقيت هذا البريد الإلكتروني عن طريق الخطأ ، يرجى حذف البريد الإلكتروني وتدمير أي نسخ منه. إذا لم تكن المستلم المقصود ، فسيُحظر تمامًا أي إفشاء أو نسخ أو توزيع أو أي إجراء تم اتخاذه أو حذفه ليتم الاعتماد عليه. على الرغم من أنه تم فحص هذا البريد الإلكتروني بحثًا عن احتمال وجود فيروسات الكمبيوتر قبل الإرسال ، يجب على المستلم التحقق من هذا البريد الإلكتروني وأي مرفقات بحثًا عن وجود فيروسات. لا تقبل الشركة الوطنية للأنظمة الميكانيكية أي مسؤولية عن أي ضرر ناتج عن أي فيروس ينتقل عن طريق مجال البريد الإلكتروني هذا. البيان والآراء الواردة في هذا البريد الإلكتروني هي آراء المرسل ولا تعكس بالضرورة آراء الشركة الوطنية للأنظمة الميكانيكية</span></h6><h6><span style="color: red;">Warning:</span><span style="color: rgb(127, 127, 127);"> </span><span style="color: black;">The information in this email and in any files transmitted with it is intended only for the addressee and may contain &nbsp;&nbsp;confidential and / or privileged material. Please be aware that any disclosure, copying, distribution or use of the contents of this information is prohibited. Access to this email by anyone else is unauthorized. If you have received this email in error please delete the e-mail and destroy any copies of it. If you are not the intended recipient, any disclosure, copying, distribution or any action taken or omitted to be taken in reliance on it, is strictly prohibited. Although this email has been scanned for the possible presence of computer viruses prior to dispatch, the recipient should check this email and any attachments for the presence of viruses. NCMS accepts no liability for any damage caused by any virus transmitted by this email domain. Statement and opinions expressed in this e-mail are those of the sender, and not necessarily reflect those of NCMS</span></h6><p class="ql-align-right"><br></p><p class="ql-align-right"><br></p>',
                'group' => 'mail',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_NOTIFY_RETURNED_REQUEST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '<h3 class="ql-align-right"><br></h3><h3 class="ql-align-right">نرحب بك في النظام الالكتروني للخدمات القانونية للشركة الوطنية للانظمة الميكانيكة</h3><p class="ql-align-right">نفيدكم بانه تم اعادة الطلب [[requestNo]] للتعديل وفق السبب الموضح لكم في الطلب</p><p class="ql-align-right">نأمل منكم التعديل على الطلب وفق المطلوب لنتمكن من خدمتكم.</p><p class="ql-align-right"><br></p><p class="ql-align-right"><strong>User name:</strong>&nbsp;[[name]]&nbsp;</p><p class="ql-align-right"><strong>User email:</strong>&nbsp;[[email]]</p><p class="ql-align-right"><strong>Request number:</strong>&nbsp;[[requestNo]]</p><p class="ql-align-right"><strong>Request URL: [[mailLink]]</strong></p><p><br></p><p><br></p><h6><span style="color: red;">تحذير:</span><span style="color: black;"> المعلومات الواردة في هذا البريد الإلكتروني وفي أي ملفات تم إرسالها معه موجهة فقط إلى المرسل إليه وقد تحتوي على مواد سرية و / أو مميزة. يرجى العلم بأن أي إفشاء أو نسخ أو توزيع أو استخدام محتويات هذه المعلومات محظور. الوصول إلى هذا البريد الإلكتروني من قبل أي شخص آخر غير المصرح به. إذا كنت قد تلقيت هذا البريد الإلكتروني عن طريق الخطأ ، يرجى حذف البريد الإلكتروني وتدمير أي نسخ منه. إذا لم تكن المستلم المقصود ، فسيُحظر تمامًا أي إفشاء أو نسخ أو توزيع أو أي إجراء تم اتخاذه أو حذفه ليتم الاعتماد عليه. على الرغم من أنه تم فحص هذا البريد الإلكتروني بحثًا عن احتمال وجود فيروسات الكمبيوتر قبل الإرسال ، يجب على المستلم التحقق من هذا البريد الإلكتروني وأي مرفقات بحثًا عن وجود فيروسات. لا تقبل الشركة الوطنية للأنظمة الميكانيكية أي مسؤولية عن أي ضرر ناتج عن أي فيروس ينتقل عن طريق مجال البريد الإلكتروني هذا. البيان والآراء الواردة في هذا البريد الإلكتروني هي آراء المرسل ولا تعكس بالضرورة آراء الشركة الوطنية للأنظمة الميكانيكية</span></h6><h6><span style="color: red;">Warning:</span><span style="color: rgb(127, 127, 127);"> </span><span style="color: black;">The information in this email and in any files transmitted with it is intended only for the addressee and may contain&nbsp;&nbsp;confidential and / or privileged material. Please be aware that any disclosure, copying, distribution or use of the contents of this information is prohibited. Access to this email by anyone else is unauthorized. If you have received this email in error please delete the e-mail and destroy any copies of it. If you are not the intended recipient, any disclosure, copying, distribution or any action taken or omitted to be taken in reliance on it, is strictly prohibited. Although this email has been scanned for the possible presence of computer viruses prior to dispatch, the recipient should check this email and any attachments for the presence of viruses. NCMS accepts no liability for any damage caused by any virus transmitted by this email domain. Statement and opinions expressed in this e-mail are those of the sender, and not necessarily reflect those of NCMS</span></h6><p class="ql-align-right"><br></p><p class="ql-align-right"><br></p>',
                'group' => 'mail',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_NOTIFY_CLOSED_REQUEST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '<h3 class="ql-align-right">نرحب بك في النظام الالكتروني للخدمات القانونية للشركة الوطنية للانظمة الميكانيكة</h3><p class="ql-align-right">نفيدكم بانه تم اغلاق الطلب من طرق قسم الشؤون القانونية الرجاء دخول نظام للنظر في التقرير المرفع</p><p class="ql-align-right">بيانات الطلب كالاتي:</p><p class="ql-align-right">اسم مقدم الطلب:[[name]]</p><p class="ql-align-right">الايميل: [[email]]</p><p class="ql-align-right">رقم الطلب:&nbsp;[[requestNo]]</p><p class="ql-align-right">عنوان الطلب: [[mailLink]]</p><p class="ql-align-right"><br></p><h6><span style="color: red;">تحذير:</span><span style="color: black;"> المعلومات الواردة في هذا البريد الإلكتروني وفي أي ملفات تم إرسالها معه موجهة فقط إلى المرسل إليه وقد تحتوي على مواد سرية و / أو مميزة. يرجى العلم بأن أي إفشاء أو نسخ أو توزيع أو استخدام محتويات هذه المعلومات محظور. الوصول إلى هذا البريد الإلكتروني من قبل أي شخص آخر غير المصرح به. إذا كنت قد تلقيت هذا البريد الإلكتروني عن طريق الخطأ ، يرجى حذف البريد الإلكتروني وتدمير أي نسخ منه. إذا لم تكن المستلم المقصود ، فسيُحظر تمامًا أي إفشاء أو نسخ أو توزيع أو أي إجراء تم اتخاذه أو حذفه ليتم الاعتماد عليه. على الرغم من أنه تم فحص هذا البريد الإلكتروني بحثًا عن احتمال وجود فيروسات الكمبيوتر قبل الإرسال ، يجب على المستلم التحقق من هذا البريد الإلكتروني وأي مرفقات بحثًا عن وجود فيروسات. لا تقبل الشركة الوطنية للأنظمة الميكانيكية أي مسؤولية عن أي ضرر ناتج عن أي فيروس ينتقل عن طريق مجال البريد الإلكتروني هذا. البيان والآراء الواردة في هذا البريد الإلكتروني هي آراء المرسل ولا تعكس بالضرورة آراء الشركة الوطنية للأنظمة الميكانيكية</span></h6><h6><span style="color: red;">Warning:</span><span style="color: rgb(127, 127, 127);"> </span><span style="color: black;">The information in this email and in any files transmitted with it is intended only for the addressee and may contain &nbsp;&nbsp;confidential and / or privileged material. Please be aware that any disclosure, copying, distribution or use of the contents of this information is prohibited. Access to this email by anyone else is unauthorized. If you have received this email in error please delete the e-mail and destroy any copies of it. If you are not the intended recipient, any disclosure, copying, distribution or any action taken or omitted to be taken in reliance on it, is strictly prohibited. Although this email has been scanned for the possible presence of computer viruses prior to dispatch, the recipient should check this email and any attachments for the presence of viruses. NCMS accepts no liability for any damage caused by any virus transmitted by this email domain. Statement and opinions expressed in this e-mail are those of the sender, and not necessarily reflect those of NCMS</span></h6><p class="ql-align-right"><br></p><p class="ql-align-right"><br></p>',
                'group' => 'mail',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_NOTIFY_CHANGE_REQUEST_STATUS');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '<h3 class="ql-align-right">نرحب بك في النظام الالكتروني للخدمات القانونية للشركة الوطنية للانظمة الميكانيكة</h3><p class="ql-align-right">نفيدكم بان الطلب رقم [[requestNo]] تم تغير حالته الى <strong>Status:</strong>&nbsp;[[status]]</p><p class="ql-align-right">بيانات الطلب كالاتي:</p><p class="ql-align-right">اسم مقدم الطلب:[[name]]</p><p class="ql-align-right">الايميل: [[email]]</p><p class="ql-align-right">رقم الطلب:&nbsp;[[requestNo]]</p><p class="ql-align-right">حالة الطلب: [[status]]</p><p class="ql-align-right"><br></p><p class="ql-align-right"><br></p><h6><span style="color: red;">تحذير:</span><span style="color: black;"> المعلومات الواردة في هذا البريد الإلكتروني وفي أي ملفات تم إرسالها معه موجهة فقط إلى المرسل إليه وقد تحتوي على مواد سرية و / أو مميزة. يرجى العلم بأن أي إفشاء أو نسخ أو توزيع أو استخدام محتويات هذه المعلومات محظور. الوصول إلى هذا البريد الإلكتروني من قبل أي شخص آخر غير المصرح به. إذا كنت قد تلقيت هذا البريد الإلكتروني عن طريق الخطأ ، يرجى حذف البريد الإلكتروني وتدمير أي نسخ منه. إذا لم تكن المستلم المقصود ، فسيُحظر تمامًا أي إفشاء أو نسخ أو توزيع أو أي إجراء تم اتخاذه أو حذفه ليتم الاعتماد عليه. على الرغم من أنه تم فحص هذا البريد الإلكتروني بحثًا عن احتمال وجود فيروسات الكمبيوتر قبل الإرسال ، يجب على المستلم التحقق من هذا البريد الإلكتروني وأي مرفقات بحثًا عن وجود فيروسات. لا تقبل الشركة الوطنية للأنظمة الميكانيكية أي مسؤولية عن أي ضرر ناتج عن أي فيروس ينتقل عن طريق مجال البريد الإلكتروني هذا. البيان والآراء الواردة في هذا البريد الإلكتروني هي آراء المرسل ولا تعكس بالضرورة آراء الشركة الوطنية للأنظمة الميكانيكية</span></h6><h6><span style="color: red;">Warning:</span><span style="color: rgb(127, 127, 127);"> </span><span style="color: black;">The information in this email and in any files transmitted with it is intended only for the addressee and may contain &nbsp;&nbsp;confidential and / or privileged material. Please be aware that any disclosure, copying, distribution or use of the contents of this information is prohibited. Access to this email by anyone else is unauthorized. If you have received this email in error please delete the e-mail and destroy any copies of it. If you are not the intended recipient, any disclosure, copying, distribution or any action taken or omitted to be taken in reliance on it, is strictly prohibited. Although this email has been scanned for the possible presence of computer viruses prior to dispatch, the recipient should check this email and any attachments for the presence of viruses. NCMS accepts no liability for any damage caused by any virus transmitted by this email domain. Statement and opinions expressed in this e-mail are those of the sender, and not necessarily reflect those of NCMS</span></h6><p class="ql-align-right"><br></p>',
                'group' => 'mail',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('MAIL_NOTIFY_APPROVE_SECRET_REQUEST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '<h3 class="ql-align-right">نرحب بك في النظام الالكتروني للخدمات القانونية للشركة الوطنية للانظمة الميكانيكة</h3><p class="ql-align-right">نفيدكم بان الطلب رقم [[requestNo]] تم تغير حالته الى <strong>Status:</strong>&nbsp;[[status]]</p><p class="ql-align-right">بيانات الطلب كالاتي:</p><p class="ql-align-right">اسم مقدم الطلب:[[name]]</p><p class="ql-align-right">الايميل: [[email]]</p><p class="ql-align-right">رقم الطلب:&nbsp;[[requestNo]]</p><p class="ql-align-right">عنوان الطلب: [[mailLink]]</p><p><br></p><p><br></p><h6><span style="color: red;">تحذير:</span><span style="color: black;"> المعلومات الواردة في هذا البريد الإلكتروني وفي أي ملفات تم إرسالها معه موجهة فقط إلى المرسل إليه وقد تحتوي على مواد سرية و / أو مميزة. يرجى العلم بأن أي إفشاء أو نسخ أو توزيع أو استخدام محتويات هذه المعلومات محظور. الوصول إلى هذا البريد الإلكتروني من قبل أي شخص آخر غير المصرح به. إذا كنت قد تلقيت هذا البريد الإلكتروني عن طريق الخطأ ، يرجى حذف البريد الإلكتروني وتدمير أي نسخ منه. إذا لم تكن المستلم المقصود ، فسيُحظر تمامًا أي إفشاء أو نسخ أو توزيع أو أي إجراء تم اتخاذه أو حذفه ليتم الاعتماد عليه. على الرغم من أنه تم فحص هذا البريد الإلكتروني بحثًا عن احتمال وجود فيروسات الكمبيوتر قبل الإرسال ، يجب على المستلم التحقق من هذا البريد الإلكتروني وأي مرفقات بحثًا عن وجود فيروسات. لا تقبل الشركة الوطنية للأنظمة الميكانيكية أي مسؤولية عن أي ضرر ناتج عن أي فيروس ينتقل عن طريق مجال البريد الإلكتروني هذا. البيان والآراء الواردة في هذا البريد الإلكتروني هي آراء المرسل ولا تعكس بالضرورة آراء الشركة الوطنية للأنظمة الميكانيكية</span></h6><h6><span style="color: red;">Warning:</span><span style="color: rgb(127, 127, 127);"> </span><span style="color: black;">The information in this email and in any files transmitted with it is intended only for the addressee and may contain &nbsp;&nbsp;confidential and / or privileged material. Please be aware that any disclosure, copying, distribution or use of the contents of this information is prohibited. Access to this email by anyone else is unauthorized. If you have received this email in error please delete the e-mail and destroy any copies of it. If you are not the intended recipient, any disclosure, copying, distribution or any action taken or omitted to be taken in reliance on it, is strictly prohibited. Although this email has been scanned for the possible presence of computer viruses prior to dispatch, the recipient should check this email and any attachments for the presence of viruses. NCMS accepts no liability for any damage caused by any virus transmitted by this email domain. Statement and opinions expressed in this e-mail are those of the sender, and not necessarily reflect those of NCMS</span></h6><p><br></p>',
                'group' => 'mail',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('ASSIGN_REQUEST');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => false,
                'value' => '1',
                'group' => 'request',
                'type' => 'select',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('TITLE');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'login',
                'type' => 'text',
                'editable' => true,
            ])->save();
        }
        $setting = $this->findSetting('DESCRIPTION');
        if (!$setting->exists) {
            $setting->fill([
                'isEnv' => true,
                'value' => '',
                'group' => 'login',
                'type' => 'textarea',
                'editable' => true,
            ])->save();
        }
    }

    protected function findSetting($key)
    {
        return Setting::firstOrNew(['key' => $key]);
    }
}
