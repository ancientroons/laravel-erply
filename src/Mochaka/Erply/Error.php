<?php

namespace Mochaka\Erply;

class Error {

	public static function code($code)
	{
		$errors = array(
			'1000' => 'API is under maintenance, please try again in a couple of minutes.',
			'1001' => 'Account not found.',
			'1002' => 'Hourly request limit (by default 1000 requests) has been exceeded for this account. Please resume next hour.',
			'1003' => 'Cannot connect to account database.',
			'1005' => 'API call (input parameter "request") not specified, or unknown API call.',
			'1006' => 'This API call is not available on this account. (Account needs upgrading, or an extra module needs to be installed.)',
			'1007' => 'Unknown output format requested; input parameter "responseType" should be either "JSON" or "XML".',
			'1008' => 'Database is under regular maintenance, please try again in a couple of minutes.',
			'1009' => 'This API call requires authentication parameters (a session key, authentication key, or service key), but none were found.',
			'1010' => 'Required parameters are missing. (Attribute "errorField" indicates the missing input parameter.)',
			'1011' => 'Invalid classifier ID, there is no such item. (Attribute "errorField" indicates the invalid input parameter.)',
			'1012' => 'A parameter must have a unique value. (Attribute "errorField" indicates the invalid input parameter.)',
			'1013' => 'Inconsistent parameter set (for example, both product and service IDs specified for an invoice row).',
			'1014' => 'Incorrect data type or format. (Attribute "errorField" indicates the invalid input parameter.)',
			'1015' => 'Malformed request (eg. parameters containing invalid characters).',
			'1016' => 'Invalid value. (Attribute "errorField" indicates the field that contains an invalid value.)',
			'1017' => 'Document has been confirmed and its contents and warehouse ID cannot be edited any more.',
			'1020' => 'Bulk API call contained more than 100 sub-requests (max 100 allowed). The whole request has been ignored.',
			'1021' => 'Another instance of the same report is currently running. Please wait and try again in a minute. (For long-running reports, API processes incoming requests only one at a time.)',
			'1040' => 'Invalid coupon identifier – such coupon has not been issued.',
			'1041' => 'Invalid coupon identifier – this coupon has already been redeemed.',
			'1042' => 'Customer does not have enough reward points.',
			'1043' => 'Employee already has an appointment on that time slot. Please choose a different start and end time for appointment.',
			'1044' => 'Default length for this service has not been defined in Erply backend – cannot suggest possible time slots.',
			'1045' => 'Invalid coupon identifier – this coupon has expired.',
			'1050' => 'Username/password missing.',
			'1051' => 'Login failed.',
			'1052' => 'User has been temporarily blocked because of repeated unsuccessful login attempts.',
			'1053' => 'No password has been set for this user, therefore the user cannot be logged in.',
			'1054' => 'API session has expired. Please call API "verifyUser" again (with correct credentials) to receive a new session key.',
			'1055' => 'Supplied session key is invalid; session not found.',
			'1056' => 'Supplied session key is too old. User switching is no longer possible with this session key, please perform a full re-authentication via API "verifyUser".',
			'1057' => 'Your time-limited demo account has expired. Please create a new ERPLY demo account, or sign up for a paid account.',
			'1060' => 'No viewing rights (in this module/for this item).',
			'1061' => 'No adding rights (in this module).',
			'1062' => 'No editing rights (in this module/for this item).',
			'1063' => 'No deleting rights (in this module/for this item).',
			'1064' => 'User does not have access to this location (store, warehouse).',
			'1065' => 'This user account does not have API access. (It may be limited to POS or Erply backend operations only.)',
			'1071' => 'This customer can buy for a full up-front payment only.',
			'1080' => 'Printing service is not running at the moment. (User can turn printing service on from their Erply account).',
			'1081' => 'E-mail sending failed.',
			'1082' => 'E-mail sending has been incorrectly set up, review settings in ERPLY. (Missing sender’s address or empty message content).',
			'1090' => 'No file attached.',
			'1091' => 'Attached file is not encoded with Base64.',
			'1092' => 'Attached file exceeds allowed size limit.',
			);

		return $errors[$code];
	}
}