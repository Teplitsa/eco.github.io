<?php if( !defined('WPINC') ) die;
/**
 * Leyka_Yandex_phyz_Gateway class
 */

class Leyka_Yandex_Phyz_Gateway extends Leyka_Gateway {

    protected static $_instance;

    protected function _set_attributes() {

        $this->_id = 'yandex_phyz';
        $this->_title = __('Yandex.Money for physical persons', 'leyka');
        $this->_docs_link = '//leyka.te-st.ru/docs/podklyuchenie-yandeks-dengi-dlya-fizicheskih-lits/';
    }

    protected function _set_options_defaults() {

        if($this->_options) // Create Gateway options, if needed
            return;

        $this->_options = array(
            'yandex_money_account' => array(
                'type' => 'text', // html, rich_html, select, radio, checkbox, multi_checkbox  
                'value' => '',
                'default' => '',
                'title' => __('Yandex account ID', 'leyka'),
                'description' => __("Please, enter your Yandex.Money account ID here. It's else known as Yandex.wallet number.", 'leyka'),
                'required' => 1,
                'placeholder' => __('Ex., 4100111111111111', 'leyka'),
                'list_entries' => array(), // For select, radio & checkbox fields
                'validation_rules' => array(), // List of regexp?..
            ),
            'yandex_money_secret' => array(
                'type' => 'text', // html, rich_html, select, radio, checkbox, multi_checkbox  
                'value' => '',
                'default' => '',
                'title' => __('Yandex account API secret', 'leyka'),
                'description' => __('Please, enter your Yandex.Money account API secret string here.', 'leyka'),
                'required' => 1,
                'placeholder' => __('Ex., QweR+1TYUIo/p2aS3DFgHJ4K5', 'leyka'),
                'list_entries' => array(), // For select, radio & checkbox fields
                'validation_rules' => array(), // List of regexp?..
            ),
        );
    }

    protected function _initialize_pm_list() {

        if(empty($this->_payment_methods['yandex_phyz_card'])) {
            $this->_payment_methods['yandex_phyz_card'] = Leyka_Yandex_Phyz_Card::get_instance();
        }
        if(empty($this->_payment_methods['yandex_phyz_money'])) {
            $this->_payment_methods['yandex_phyz_money'] = Leyka_Yandex_Phyz_Money::get_instance();
        }
    }

    public function process_form($gateway_id, $pm_id, $donation_id, $form_data) {
    }

    public function submission_redirect_url($current_url, $pm_id) {

        switch($pm_id) {
            case 'yandex_phyz_money':
            case 'yandex_phyz_card':
                return 'https://money.yandex.ru/quickpay/confirm.xml';
            default:
                return $current_url;
        }
    }

    public function submission_form_data($form_data_vars, $pm_id, $donation_id) {

        $donation = new Leyka_Donation($donation_id);
        $campaign = new Leyka_Campaign($donation->campaign_id);

        switch($pm_id) {
            case 'yandex_phyz_money': $payment_type = 'PC'; break;
            case 'yandex_phyz_card': $payment_type = 'AC'; break;
            default:
                $payment_type = '';
        }

		$name = esc_attr(get_bloginfo('name').': '.__('donation', 'leyka'));

        return array(
            'receiver' => leyka_options()->opt('yandex_money_account'),
            'sum' => $donation->amount,
            'formcomment' => $name,
			'short-dest' => $name,
			'targets' => $campaign->payment_title ? esc_attr($campaign->payment_title) : $name,
			'quickpay-form' => 'donate',
            'label' => $donation_id,
            'paymentType' => $payment_type,
            'shopSuccessURL' => leyka_get_success_page_url(),
            'shopFailURL' => leyka_get_failure_page_url(),
            'cps_email' => $donation->donor_email,
//            '' => ,
        );
    }

    public function log_gateway_fields($donation_id) {
    }

    /** Wrapper method to answer the checkOrder service calls */
    private function _check_order_answer($is_error = false, $message = '', $tech_message = '') {

        $is_error = !!$is_error;
        $tech_message = $tech_message ? $tech_message : $message;

        $_POST['operation_id'] = empty($_POST['operation_id']) ? 0 : (int)$_POST['operation_id'];

        if($is_error) {
            die('<?xml version="1.0" encoding="UTF-8"?>
<checkOrderResponse performedDatetime="'.date(DATE_ATOM).'"
code="1000" operation_id="'.$_POST['operation_id'].'"
account_id="'.leyka_options()->opt('yandex_money_account').'"
message="'.$message.'"
techMessage="'.$tech_message.'"/>');
        } else {
            die('<?xml version="1.0" encoding="UTF-8"?>
<checkOrderResponse performedDatetime="'.date(DATE_ATOM).'"
code="0" operation_id="'.$_POST['operation_id'].'"
account_id="'.leyka_options()->opt('yandex_money_account').'"/>');
        }
    }

    public function _handle_service_calls($call_type = '') {

		error_log_yandex_phyz("\n\n---- $call_type ----\n\n".print_r($_REQUEST, true));

        $donation_id = empty($_POST['label']) ? 0 : (int)$_POST['label']; // Donation ID
        $amount = empty($_POST['withdraw_amount']) ? 0.0 : (float)$_POST['withdraw_amount'];

        error_log_yandex_phyz("Label=$donation_id\n");
        error_log_yandex_phyz("Amount=$amount\n");

        if( !$donation_id ) {

            error_log_yandex_phyz("Donation ID is empty\n");
            return;

        } else if( !$amount ) {

            error_log_yandex_phyz("Donation amount is empty\n");
            return;

        }

        $donation = new Leyka_Donation($donation_id);

        error_log_yandex_phyz("Donation initialized\n");
        error_log_yandex_phyz(print_r($donation, TRUE)."\n");

        $sha1 = sha1(implode('&', array(
            empty($_POST['notification_type']) ? '' : $_POST['notification_type'],
            empty($_POST['operation_id']) ? '' : $_POST['operation_id'],
            empty($_POST['amount']) ? '' : $_POST['amount'],
            empty($_POST['currency']) ? '' : $_POST['currency'],
            empty($_POST['datetime']) ? '' : $_POST['datetime'],
            empty($_POST['sender']) ? '' : $_POST['sender'],
            empty($_POST['codepro']) ? '' : $_POST['codepro'],
            leyka_options()->opt('yandex_money_secret'),
            $donation_id
        )));

        error_log_yandex_phyz("sha1=$sha1\n");

        if(empty($_POST['sha1_hash']) || $sha1 != @$_POST['sha1_hash']) {

            error_log_yandex_phyz("Invalid response sha1_hash\n");
            $this->_check_order_answer(1, __('Sorry, there is some tech error on our side. Your payment will be cancelled.', 'leyka'), __('Invalid response sha1_hash', 'leyka'));

        } else if($donation) {

            error_log_yandex_phyz("Donation OK\n");
            error_log_yandex_phyz('$donation->sum='.$donation->sum."\n");
            error_log_yandex_phyz('$donation->status='.$donation->status."\n");

            if($donation->sum != $amount) {

                error_log_yandex_phyz("Donation sum is unmatched\n");
                $this->_check_order_answer(1, __('Sorry, there is some tech error on our side. Your payment will be cancelled.', 'leyka'), __('Donation sum is unmatched', 'leyka'));

            } else if($donation->status != 'funded') {

                error_log_yandex_phyz("Donation is funded\n");

                if( !empty($_POST['notification_type']) ) { // Update a donation's actual PM, if needed

                    $actual_pm = $_POST['notification_type'] == 'card-incoming' ?
                        'yandex_phyz_card' : 'yandex_phyz_money';

                    if($donation->pm_id != $_POST['notification_type'])
                        $donation->pm_id = $actual_pm;
                }

                $donation->add_gateway_response($_POST);
                $donation->status = 'funded';
                Leyka_Donation_Management::send_all_emails($donation->id);

            } else {
                error_log_yandex_phyz("Already funded\n");
            }

            $this->_check_order_answer();

        } else {

            error_log_yandex_phyz("There is no donation in Leyka DB\n");
            $this->_check_order_answer(1, __('Sorry, there is some tech error on our side. Your payment will be cancelled.', 'leyka'), __('Unregistered donation ID', 'leyka'));
        }
    }

    public function get_gateway_response_formatted(Leyka_Donation $donation) {

        if( !$donation->gateway_response ) {
            return array();
        }

        $response_vars = maybe_unserialize($donation->gateway_response);
        if( !$response_vars || !is_array($response_vars) ) {
            return array();
        }

		$payment_type = '';
		if($response_vars['notification_type'] == 'p2p-incoming') {
			$payment_type = __('Using Yandex.Money Account', 'leyka');
		} else if($response_vars['notification_type'] == 'card-incoming') {
			$payment_type = __('Using Banking Card', 'leyka');
		}

        return array(
            __('Last response operation:', 'leyka') => __('Donation confirmation', 'leyka'),
            __('Yandex payment type:', 'leyka') => $payment_type,
            __('Gateway invoice ID:', 'leyka') => $response_vars['operation_id'],
            __('Full donation amount:', 'leyka') =>
                (float)$response_vars['withdraw_amount'].' '.$donation->currency_label,
            __('Donation amount after gateway commission:', 'leyka') =>
                (float)$response_vars['amount'].' '.$donation->currency_label,
            __("Gateway's donor ID:", 'leyka') => $response_vars['sender'],
            __('Response date:', 'leyka') => date('d.m.Y, H:i:s', strtotime($response_vars['datetime'])),
        );
    }
}


class Leyka_Yandex_Phyz_Money extends Leyka_Payment_Method {

    protected static $_instance = null;
    
    public function _set_attributes() {

        $this->_id = 'yandex_phyz_money';
        $this->_gateway_id = 'yandex_phyz';

        $this->_label_backend = __('Virtual cash Yandex.Money', 'leyka');
        $this->_label = __('Virtual cash Yandex.Money', 'leyka');

        // The description won't be setted here - it requires the PM option being configured at this time (which is not)

        $this->_icons = apply_filters('leyka_icons_'.$this->_gateway_id.'_'.$this->_id, array(
            LEYKA_PLUGIN_BASE_URL.'gateways/yandex_phyz/icons/yandex_phyz_money_s.png',
//            LEYKA_PLUGIN_BASE_URL.'gateways/quittance/icons/sber_s.png',
        ));

        $this->_supported_currencies[] = 'rur';

        $this->_default_currency = 'rur';
    }

    protected function _set_options_defaults() {

        if($this->_options){
            return;
        }

        $this->_options = array(
            $this->full_id.'_description' => array(
                'type' => 'html',
                'default' => __("Yandex.Money is a simple and safe payment system to pay for goods and services through internet. You will have to fill a payment form, you will be redirected to the <a href='https://money.yandex.ru/'>Yandex.Money website</a> to confirm your payment. If you haven't got a Yandex.Money account, you can create it there.", 'leyka'),
                'title' => __('Yandex.Money description', 'leyka'),
                'description' => __('Please, enter Yandex.Money payment description that will be shown to the donor when this payment method will be selected for using.', 'leyka'),
                'required' => 0,
                'validation_rules' => array(), // List of regexp?..
            ),
        );
    }
}


class Leyka_Yandex_Phyz_Card extends Leyka_Payment_Method {

    protected static $_instance = null;

    public function _set_attributes() {

        $this->_id = 'yandex_phyz_card';
        $this->_gateway_id = 'yandex_phyz';

        $this->_label = __('Payment with Banking Card Yandex', 'leyka');
        $this->_label_backend = $this->_label;

        $this->_icons = apply_filters('leyka_icons_'.$this->_gateway_id.'_'.$this->_id, array(
//            LEYKA_PLUGIN_BASE_URL.'gateways/yandex_phyz/icons/yandex_phyz_money_s.png',
            LEYKA_PLUGIN_BASE_URL.'gateways/yandex_phyz/icons/visa.png',
            LEYKA_PLUGIN_BASE_URL.'gateways/yandex_phyz/icons/master.png',
        ));

        $this->_supported_currencies[] = 'rur';

        $this->_default_currency = 'rur';
    }

    protected function _set_options_defaults() {

        if($this->_options){
            return;
        }

        $this->_options = array(
            $this->full_id.'_description' => array(
                'type' => 'html',
                'default' => __('Yandex.Money allows a simple and safe way to pay for goods and services with bank cards through internet. You will have to fill a payment form, you will be redirected to the <a href="https://money.yandex.ru/">Yandex.Money website</a> to enter your bank card data and to confirm your payment.', 'leyka'),
                'title' => __('Yandex bank card payment description', 'leyka'),
                'description' => __('Please, enter Yandex.Money bank cards payment description that will be shown to the donor when this payment method will be selected for using.', 'leyka'),
                'required' => 0,
                'validation_rules' => array(), // List of regexp?..
            ),
        );
    }
}

function error_log_yandex_phyz($string) {
	error_log($string, 3, WP_CONTENT_DIR.'/uploads/phyz-error.log');
}

function leyka_add_gateway_yandex_phyz() { // Use named function to leave a possibility to remove/replace it on the hook
    leyka_add_gateway(Leyka_Yandex_Phyz_Gateway::get_instance());
}
add_action('leyka_init_actions', 'leyka_add_gateway_yandex_phyz');