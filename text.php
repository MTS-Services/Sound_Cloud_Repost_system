<?php

Stripe\Subscription {#1781 // app\Http\Controllers\Backend\User\PaymentController.php:195
  #_opts: 
Stripe\Util
\
RequestOptions
 {#1787
    +headers: []
    +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
    +apiBase: null
    +maxNetworkRetries: null
    apiKey: "sk_test_***********************************************************************************************5gJx"
    headers: []
    apiBase: null
    maxNetworkRetries: null
  }
  #_originalValues: array:47 [
    "id" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
    "object" => "subscription"
    "application" => null
    "application_fee_percent" => null
    "automatic_tax" => array:3 [
      "disabled_reason" => null
      "enabled" => false
      "liability" => null
    ]
    "billing_cycle_anchor" => 1765345618
    "billing_cycle_anchor_config" => null
    "billing_mode" => array:2 [
      "flexible" => null
      "type" => "classic"
    ]
    "billing_thresholds" => null
    "cancel_at" => null
    "cancel_at_period_end" => false
    "canceled_at" => null
    "cancellation_details" => array:3 [
      "comment" => null
      "feedback" => null
      "reason" => null
    ]
    "collection_method" => "charge_automatically"
    "created" => 1765345618
    "currency" => "usd"
    "customer" => "cus_TZpBbThDxNdUzj"
    "customer_account" => null
    "days_until_due" => null
    "default_payment_method" => null
    "default_source" => null
    "default_tax_rates" => []
    "description" => null
    "discounts" => []
    "ended_at" => null
    "invoice_settings" => array:2 [
      "account_tax_ids" => null
      "issuer" => array:1 [
        "type" => "self"
      ]
    ]
    "items" => array:5 [
      "object" => "list"
      "data" => array:1 [
        0 => array:13 [
          "id" => "si_TZpuwoMh1mYQmp"
          "object" => "subscription_item"
          "billing_thresholds" => null
          "created" => 1765345618
          "current_period_end" => 1768024018
          "current_period_start" => 1765345618
          "discounts" => []
          "metadata" => []
          "plan" => array:19 [
            "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
            "object" => "plan"
            "active" => true
            "amount" => 2500
            "amount_decimal" => "2500"
            "billing_scheme" => "per_unit"
            "created" => 1765345617
            "currency" => "usd"
            "interval" => "month"
            "interval_count" => 1
            "livemode" => false
            "metadata" => array:2 [
              "billing_cycle" => "monthly"
              "plan_id" => "2"
            ]
            "meter" => null
            "nickname" => null
            "product" => "prod_TZpucAeinrzdzH"
            "tiers_mode" => null
            "transform_usage" => null
            "trial_period_days" => null
            "usage_type" => "licensed"
          ]
          "price" => array:19 [
            "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
            "object" => "price"
            "active" => true
            "billing_scheme" => "per_unit"
            "created" => 1765345617
            "currency" => "usd"
            "custom_unit_amount" => null
            "livemode" => false
            "lookup_key" => null
            "metadata" => array:2 [
              "billing_cycle" => "monthly"
              "plan_id" => "2"
            ]
            "nickname" => null
            "product" => "prod_TZpucAeinrzdzH"
            "recurring" => array:5 [
              "interval" => "month"
              "interval_count" => 1
              "meter" => null
              "trial_period_days" => null
              "usage_type" => "licensed"
            ]
            "tax_behavior" => "unspecified"
            "tiers_mode" => null
            "transform_quantity" => null
            "type" => "recurring"
            "unit_amount" => 2500
            "unit_amount_decimal" => "2500"
          ]
          "quantity" => 1
          "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
          "tax_rates" => []
        ]
      ]
      "has_more" => false
      "total_count" => 1
      "url" => "/v1/subscription_items?subscription=sub_1ScgEoISIl8QzoFfGbttNG7T"
    ]
    "latest_invoice" => array:74 [
      "id" => "in_1ScgEoISIl8QzoFfjn5myIdr"
      "object" => "invoice"
      "account_country" => "AT"
      "account_name" => "Soundcloud "
      "account_tax_ids" => null
      "amount_due" => 2500
      "amount_overpaid" => 0
      "amount_paid" => 0
      "amount_remaining" => 2500
      "amount_shipping" => 0
      "application" => null
      "attempt_count" => 0
      "attempted" => false
      "auto_advance" => false
      "automatic_tax" => array:5 [
        "disabled_reason" => null
        "enabled" => false
        "liability" => null
        "provider" => null
        "status" => null
      ]
      "automatically_finalizes_at" => null
      "billing_reason" => "subscription_create"
      "collection_method" => "charge_automatically"
      "created" => 1765345618
      "currency" => "usd"
      "custom_fields" => null
      "customer" => "cus_TZpBbThDxNdUzj"
      "customer_account" => null
      "customer_address" => null
      "customer_email" => "zijeraw@mailinator.com"
      "customer_name" => "Scarlett Richard"
      "customer_phone" => null
      "customer_shipping" => null
      "customer_tax_exempt" => "none"
      "customer_tax_ids" => []
      "default_payment_method" => null
      "default_source" => null
      "default_tax_rates" => []
      "description" => null
      "discounts" => []
      "due_date" => null
      "effective_at" => 1765345618
      "ending_balance" => 0
      "footer" => null
      "from_invoice" => null
      "hosted_invoice_url" => "https://invoice.stripe.com/i/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ?s=ap"
      "invoice_pdf" => "https://pay.stripe.com/invoice/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ/pdf?s=ap"
      "issuer" => array:1 [
        "type" => "self"
      ]
      "last_finalization_error" => null
      "latest_revision" => null
      "lines" => array:5 [
        "object" => "list"
        "data" => array:1 [
          0 => array:17 [
            "id" => "il_1ScgEoISIl8QzoFfQvZ5R3KJ"
            "object" => "line_item"
            "amount" => 2500
            "currency" => "usd"
            "description" => "1 × Premium Plan - Month Plan (at $25.00 / month)"
            "discount_amounts" => []
            "discountable" => true
            "discounts" => []
            "invoice" => "in_1ScgEoISIl8QzoFfjn5myIdr"
            "livemode" => false
            "metadata" => array:4 [
              "order_id" => "7"
              "plan_id" => "2"
              "source_id" => "2"
              "user_urn" => "urn:sc:users:1001"
            ]
            "parent" => array:3 [
              "invoice_item_details" => null
              "subscription_item_details" => array:5 [
                "invoice_item" => null
                "proration" => false
                "proration_details" => array:1 [
                  "credited_items" => null
                ]
                "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
                "subscription_item" => "si_TZpuwoMh1mYQmp"
              ]
              "type" => "subscription_item_details"
            ]
            "period" => array:2 [
              "end" => 1768024018
              "start" => 1765345618
            ]
            "pretax_credit_amounts" => []
            "pricing" => array:3 [
              "price_details" => array:2 [
                "price" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                "product" => "prod_TZpucAeinrzdzH"
              ]
              "type" => "price_details"
              "unit_amount_decimal" => "2500"
            ]
            "quantity" => 1
            "taxes" => []
          ]
        ]
        "has_more" => false
        "total_count" => 1
        "url" => "/v1/invoices/in_1ScgEoISIl8QzoFfjn5myIdr/lines"
      ]
      "livemode" => false
      "metadata" => []
      "next_payment_attempt" => null
      "number" => "OKMWK4LU-0003"
      "on_behalf_of" => null
      "parent" => array:3 [
        "quote_details" => null
        "subscription_details" => array:2 [
          "metadata" => array:4 [
            "order_id" => "7"
            "plan_id" => "2"
            "source_id" => "2"
            "user_urn" => "urn:sc:users:1001"
          ]
          "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
        ]
        "type" => "subscription_details"
      ]
      "payment_settings" => array:3 [
        "default_mandate" => null
        "payment_method_options" => null
        "payment_method_types" => null
      ]
      "period_end" => 1765345618
      "period_start" => 1765345618
      "post_payment_credit_notes_amount" => 0
      "pre_payment_credit_notes_amount" => 0
      "receipt_number" => null
      "rendering" => null
      "shipping_cost" => null
      "shipping_details" => null
      "starting_balance" => 0
      "statement_descriptor" => null
      "status" => "open"
      "status_transitions" => array:4 [
        "finalized_at" => 1765345618
        "marked_uncollectible_at" => null
        "paid_at" => null
        "voided_at" => null
      ]
      "subtotal" => 2500
      "subtotal_excluding_tax" => 2500
      "test_clock" => null
      "total" => 2500
      "total_discount_amounts" => []
      "total_excluding_tax" => 2500
      "total_pretax_credit_amounts" => []
      "total_taxes" => []
      "webhooks_delivered_at" => 1765345618
    ]
    "livemode" => false
    "metadata" => array:4 [
      "order_id" => "7"
      "plan_id" => "2"
      "source_id" => "2"
      "user_urn" => "urn:sc:users:1001"
    ]
    "next_pending_invoice_item_invoice" => null
    "on_behalf_of" => null
    "pause_collection" => null
    "payment_settings" => array:3 [
      "payment_method_options" => null
      "payment_method_types" => null
      "save_default_payment_method" => "on_subscription"
    ]
    "pending_invoice_item_interval" => null
    "pending_setup_intent" => null
    "pending_update" => null
    "plan" => array:19 [
      "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
      "object" => "plan"
      "active" => true
      "amount" => 2500
      "amount_decimal" => "2500"
      "billing_scheme" => "per_unit"
      "created" => 1765345617
      "currency" => "usd"
      "interval" => "month"
      "interval_count" => 1
      "livemode" => false
      "metadata" => array:2 [
        "billing_cycle" => "monthly"
        "plan_id" => "2"
      ]
      "meter" => null
      "nickname" => null
      "product" => "prod_TZpucAeinrzdzH"
      "tiers_mode" => null
      "transform_usage" => null
      "trial_period_days" => null
      "usage_type" => "licensed"
    ]
    "quantity" => 1
    "schedule" => null
    "start_date" => 1765345618
    "status" => "incomplete"
    "test_clock" => null
    "transfer_data" => null
    "trial_end" => null
    "trial_settings" => array:1 [
      "end_behavior" => array:1 [
        "missing_payment_method" => "create_invoice"
      ]
    ]
    "trial_start" => null
  ]
  #_values: array:47 [
    "id" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
    "object" => "subscription"
    "application" => null
    "application_fee_percent" => null
    "automatic_tax" => 
Stripe
\
StripeObject
 {#1806
      #_opts: 
Stripe\Util
\
RequestOptions
 {#1799
        +headers: []
        +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
        +apiBase: null
        +maxNetworkRetries: null
        apiKey: "sk_test_***********************************************************************************************5gJx"
        headers: []
        apiBase: null
        maxNetworkRetries: null
      }
      #_originalValues: array:3 [
        "disabled_reason" => null
        "enabled" => false
        "liability" => null
      ]
      #_values: array:3 [
        "disabled_reason" => null
        "enabled" => false
        "liability" => null
      ]
      #_unsavedValues: 
Stripe\Util
\
Set
 {#1802
        -_elts: []
      }
      #_transientValues: 
Stripe\Util
\
Set
 {#1800
        -_elts: []
      }
      #_retrieveOptions: []
      #_lastResponse: null
      disabled_reason: null
      enabled: false
      liability: null
    }
    "billing_cycle_anchor" => 1765345618
    "billing_cycle_anchor_config" => null
    "billing_mode" => 
Stripe
\
StripeObject
 {#1801
      #_opts: 
Stripe\Util
\
RequestOptions
 {#1795
        +headers: []
        +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
        +apiBase: null
        +maxNetworkRetries: null
        apiKey: "sk_test_***********************************************************************************************5gJx"
        headers: []
        apiBase: null
        maxNetworkRetries: null
      }
      #_originalValues: array:2 [
        "flexible" => null
        "type" => "classic"
      ]
      #_values: array:2 [
        "flexible" => null
        "type" => "classic"
      ]
      #_unsavedValues: 
Stripe\Util
\
Set
 {#1797
        -_elts: []
      }
      #_transientValues: 
Stripe\Util
\
Set
 {#1796
        -_elts: []
      }
      #_retrieveOptions: []
      #_lastResponse: null
      flexible: null
      type: "classic"
    }
    "billing_thresholds" => null
    "cancel_at" => null
    "cancel_at_period_end" => false
    "canceled_at" => null
    "cancellation_details" => 
Stripe
\
StripeObject
 {#1798
      #_opts: 
Stripe\Util
\
RequestOptions
 {#1791
        +headers: []
        +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
        +apiBase: null
        +maxNetworkRetries: null
        apiKey: "sk_test_***********************************************************************************************5gJx"
        headers: []
        apiBase: null
        maxNetworkRetries: null
      }
      #_originalValues: array:3 [
        "comment" => null
        "feedback" => null
        "reason" => null
      ]
      #_values: array:3 [
        "comment" => null
        "feedback" => null
        "reason" => null
      ]
      #_unsavedValues: 
Stripe\Util
\
Set
 {#1793
        -_elts: []
      }
      #_transientValues: 
Stripe\Util
\
Set
 {#1792
        -_elts: []
      }
      #_retrieveOptions: []
      #_lastResponse: null
      comment: null
      feedback: null
      reason: null
    }
    "collection_method" => "charge_automatically"
    "created" => 1765345618
    "currency" => "usd"
    "customer" => "cus_TZpBbThDxNdUzj"
    "customer_account" => null
    "days_until_due" => null
    "default_payment_method" => null
    "default_source" => null
    "default_tax_rates" => []
    "description" => null
    "discounts" => []
    "ended_at" => null
    "invoice_settings" => 
Stripe
\
StripeObject
 {#1794
      #_opts: 
Stripe\Util
\
RequestOptions
 {#1746
        +headers: []
        +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
        +apiBase: null
        +maxNetworkRetries: null
        apiKey: "sk_test_***********************************************************************************************5gJx"
        headers: []
        apiBase: null
        maxNetworkRetries: null
      }
      #_originalValues: array:2 [
        "account_tax_ids" => null
        "issuer" => array:1 [
          "type" => "self"
        ]
      ]
      #_values: array:2 [
        "account_tax_ids" => null
        "issuer" => 
Stripe
\
StripeObject
 {#1747
          #_opts: 
Stripe\Util
\
RequestOptions
 {#1810
            +headers: []
            +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
            +apiBase: null
            +maxNetworkRetries: null
            apiKey: "sk_test_***********************************************************************************************5gJx"
            headers: []
            apiBase: null
            maxNetworkRetries: null
          }
          #_originalValues: array:1 [
            "type" => "self"
          ]
          #_values: array:1 [
            "type" => "self"
          ]
          #_unsavedValues: 
Stripe\Util
\
Set
 {#1749
            -_elts: []
          }
          #_transientValues: 
Stripe\Util
\
Set
 {#1750
            -_elts: []
          }
          #_retrieveOptions: []
          #_lastResponse: null
          type: "self"
        }
      ]
      #_unsavedValues: 
Stripe\Util
\
Set
 {#1744
        -_elts: []
      }
      #_transientValues: 
Stripe\Util
\
Set
 {#1745
        -_elts: []
      }
      #_retrieveOptions: []
      #_lastResponse: null
      account_tax_ids: null
      issuer: 
Stripe
\
StripeObject
 {#1747}
    }
    "items" => 
Stripe
\
Collection
 {#1743
      #_opts: 
Stripe\Util
\
RequestOptions
 {#1813
        +headers: []
        +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
        +apiBase: null
        +maxNetworkRetries: null
        apiKey: "sk_test_***********************************************************************************************5gJx"
        headers: []
        apiBase: null
        maxNetworkRetries: null
      }
      #_originalValues: array:5 [
        "object" => "list"
        "data" => array:1 [
          0 => array:13 [
            "id" => "si_TZpuwoMh1mYQmp"
            "object" => "subscription_item"
            "billing_thresholds" => null
            "created" => 1765345618
            "current_period_end" => 1768024018
            "current_period_start" => 1765345618
            "discounts" => []
            "metadata" => []
            "plan" => array:19 [
              "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
              "object" => "plan"
              "active" => true
              "amount" => 2500
              "amount_decimal" => "2500"
              "billing_scheme" => "per_unit"
              "created" => 1765345617
              "currency" => "usd"
              "interval" => "month"
              "interval_count" => 1
              "livemode" => false
              "metadata" => array:2 [
                "billing_cycle" => "monthly"
                "plan_id" => "2"
              ]
              "meter" => null
              "nickname" => null
              "product" => "prod_TZpucAeinrzdzH"
              "tiers_mode" => null
              "transform_usage" => null
              "trial_period_days" => null
              "usage_type" => "licensed"
            ]
            "price" => array:19 [
              "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
              "object" => "price"
              "active" => true
              "billing_scheme" => "per_unit"
              "created" => 1765345617
              "currency" => "usd"
              "custom_unit_amount" => null
              "livemode" => false
              "lookup_key" => null
              "metadata" => array:2 [
                "billing_cycle" => "monthly"
                "plan_id" => "2"
              ]
              "nickname" => null
              "product" => "prod_TZpucAeinrzdzH"
              "recurring" => array:5 [
                "interval" => "month"
                "interval_count" => 1
                "meter" => null
                "trial_period_days" => null
                "usage_type" => "licensed"
              ]
              "tax_behavior" => "unspecified"
              "tiers_mode" => null
              "transform_quantity" => null
              "type" => "recurring"
              "unit_amount" => 2500
              "unit_amount_decimal" => "2500"
            ]
            "quantity" => 1
            "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
            "tax_rates" => []
          ]
        ]
        "has_more" => false
        "total_count" => 1
        "url" => "/v1/subscription_items?subscription=sub_1ScgEoISIl8QzoFfGbttNG7T"
      ]
      #_values: array:5 [
        "object" => "list"
        "data" => array:1 [
          0 => 
Stripe
\
SubscriptionItem
 {#1814
            #_opts: 
Stripe\Util
\
RequestOptions
 {#1818
              +headers: []
              +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
              +apiBase: null
              +maxNetworkRetries: null
              apiKey: "sk_test_***********************************************************************************************5gJx"
              headers: []
              apiBase: null
              maxNetworkRetries: null
            }
            #_originalValues: array:13 [
              "id" => "si_TZpuwoMh1mYQmp"
              "object" => "subscription_item"
              "billing_thresholds" => null
              "created" => 1765345618
              "current_period_end" => 1768024018
              "current_period_start" => 1765345618
              "discounts" => []
              "metadata" => []
              "plan" => array:19 [
                "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                "object" => "plan"
                "active" => true
                "amount" => 2500
                "amount_decimal" => "2500"
                "billing_scheme" => "per_unit"
                "created" => 1765345617
                "currency" => "usd"
                "interval" => "month"
                "interval_count" => 1
                "livemode" => false
                "metadata" => array:2 [
                  "billing_cycle" => "monthly"
                  "plan_id" => "2"
                ]
                "meter" => null
                "nickname" => null
                "product" => "prod_TZpucAeinrzdzH"
                "tiers_mode" => null
                "transform_usage" => null
                "trial_period_days" => null
                "usage_type" => "licensed"
              ]
              "price" => array:19 [
                "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                "object" => "price"
                "active" => true
                "billing_scheme" => "per_unit"
                "created" => 1765345617
                "currency" => "usd"
                "custom_unit_amount" => null
                "livemode" => false
                "lookup_key" => null
                "metadata" => array:2 [
                  "billing_cycle" => "monthly"
                  "plan_id" => "2"
                ]
                "nickname" => null
                "product" => "prod_TZpucAeinrzdzH"
                "recurring" => array:5 [
                  "interval" => "month"
                  "interval_count" => 1
                  "meter" => null
                  "trial_period_days" => null
                  "usage_type" => "licensed"
                ]
                "tax_behavior" => "unspecified"
                "tiers_mode" => null
                "transform_quantity" => null
                "type" => "recurring"
                "unit_amount" => 2500
                "unit_amount_decimal" => "2500"
              ]
              "quantity" => 1
              "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
              "tax_rates" => []
            ]
            #_values: array:13 [
              "id" => "si_TZpuwoMh1mYQmp"
              "object" => "subscription_item"
              "billing_thresholds" => null
              "created" => 1765345618
              "current_period_end" => 1768024018
              "current_period_start" => 1765345618
              "discounts" => []
              "metadata" => 
Stripe
\
StripeObject
 {#1819
                #_opts: 
Stripe\Util
\
RequestOptions
 {#1823
                  +headers: []
                  +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                  +apiBase: null
                  +maxNetworkRetries: null
                  apiKey: "sk_test_***********************************************************************************************5gJx"
                  headers: []
                  apiBase: null
                  maxNetworkRetries: null
                }
                #_originalValues: []
                #_values: []
                #_unsavedValues: 
Stripe\Util
\
Set
 {#1821
                  -_elts: []
                }
                #_transientValues: 
Stripe\Util
\
Set
 {#1822
                  -_elts: []
                }
                #_retrieveOptions: []
                #_lastResponse: null
              }
              "plan" => 
Stripe
\
Plan
 {#1820
                #_opts: 
Stripe\Util
\
RequestOptions
 {#1827
                  +headers: []
                  +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                  +apiBase: null
                  +maxNetworkRetries: null
                  apiKey: "sk_test_***********************************************************************************************5gJx"
                  headers: []
                  apiBase: null
                  maxNetworkRetries: null
                }
                #_originalValues: array:19 [
                  "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                  "object" => "plan"
                  "active" => true
                  "amount" => 2500
                  "amount_decimal" => "2500"
                  "billing_scheme" => "per_unit"
                  "created" => 1765345617
                  "currency" => "usd"
                  "interval" => "month"
                  "interval_count" => 1
                  "livemode" => false
                  "metadata" => array:2 [
                    "billing_cycle" => "monthly"
                    "plan_id" => "2"
                  ]
                  "meter" => null
                  "nickname" => null
                  "product" => "prod_TZpucAeinrzdzH"
                  "tiers_mode" => null
                  "transform_usage" => null
                  "trial_period_days" => null
                  "usage_type" => "licensed"
                ]
                #_values: array:19 [
                  "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                  "object" => "plan"
                  "active" => true
                  "amount" => 2500
                  "amount_decimal" => "2500"
                  "billing_scheme" => "per_unit"
                  "created" => 1765345617
                  "currency" => "usd"
                  "interval" => "month"
                  "interval_count" => 1
                  "livemode" => false
                  "metadata" => 
Stripe
\
StripeObject
 {#1828
                    #_opts: 
Stripe\Util
\
RequestOptions
 {#1832
                      +headers: []
                      +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                      +apiBase: null
                      +maxNetworkRetries: null
                      apiKey: "sk_test_***********************************************************************************************5gJx"
                      headers: []
                      apiBase: null
                      maxNetworkRetries: null
                    }
                    #_originalValues: array:2 [
                      "billing_cycle" => "monthly"
                      "plan_id" => "2"
                    ]
                    #_values: array:2 [
                      "billing_cycle" => "monthly"
                      "plan_id" => "2"
                    ]
                    #_unsavedValues: 
Stripe\Util
\
Set
 {#1830
                      -_elts: []
                    }
                    #_transientValues: 
Stripe\Util
\
Set
 {#1831
                      -_elts: []
                    }
                    #_retrieveOptions: []
                    #_lastResponse: null
                    billing_cycle: "monthly"
                    plan_id: "2"
                  }
                  "meter" => null
                  "nickname" => null
                  "product" => "prod_TZpucAeinrzdzH"
                  "tiers_mode" => null
                  "transform_usage" => null
                  "trial_period_days" => null
                  "usage_type" => "licensed"
                ]
                #_unsavedValues: 
Stripe\Util
\
Set
 {#1825
                  -_elts: []
                }
                #_transientValues: 
Stripe\Util
\
Set
 {#1826
                  -_elts: []
                }
                #_retrieveOptions: []
                #_lastResponse: null
                +saveWithParent: false
                id: "price_1ScgEnISIl8QzoFfPzV32FUq"
                object: "plan"
                active: true
                amount: 2500
                amount_decimal: "2500"
                billing_scheme: "per_unit"
                created: 1765345617
                currency: "usd"
                interval: "month"
                interval_count: 1
                livemode: false
                metadata: 
Stripe
\
StripeObject
 {#1828}
                meter: null
                nickname: null
                product: "prod_TZpucAeinrzdzH"
                tiers_mode: null
                transform_usage: null
                trial_period_days: null
                usage_type: "licensed"
              }
              "price" => 
Stripe
\
Price
 {#1824
                #_opts: 
Stripe\Util
\
RequestOptions
 {#1835
                  +headers: []
                  +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                  +apiBase: null
                  +maxNetworkRetries: null
                  apiKey: "sk_test_***********************************************************************************************5gJx"
                  headers: []
                  apiBase: null
                  maxNetworkRetries: null
                }
                #_originalValues: array:19 [
                  "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                  "object" => "price"
                  "active" => true
                  "billing_scheme" => "per_unit"
                  "created" => 1765345617
                  "currency" => "usd"
                  "custom_unit_amount" => null
                  "livemode" => false
                  "lookup_key" => null
                  "metadata" => array:2 [
                    "billing_cycle" => "monthly"
                    "plan_id" => "2"
                  ]
                  "nickname" => null
                  "product" => "prod_TZpucAeinrzdzH"
                  "recurring" => array:5 [
                    "interval" => "month"
                    "interval_count" => 1
                    "meter" => null
                    "trial_period_days" => null
                    "usage_type" => "licensed"
                  ]
                  "tax_behavior" => "unspecified"
                  "tiers_mode" => null
                  "transform_quantity" => null
                  "type" => "recurring"
                  "unit_amount" => 2500
                  "unit_amount_decimal" => "2500"
                ]
                #_values: array:19 [
                  "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                  "object" => "price"
                  "active" => true
                  "billing_scheme" => "per_unit"
                  "created" => 1765345617
                  "currency" => "usd"
                  "custom_unit_amount" => null
                  "livemode" => false
                  "lookup_key" => null
                  "metadata" => 
Stripe
\
StripeObject
 {#1836
                    #_opts: 
Stripe\Util
\
RequestOptions
 {#1840
                      +headers: []
                      +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                      +apiBase: null
                      +maxNetworkRetries: null
                      apiKey: "sk_test_***********************************************************************************************5gJx"
                      headers: []
                      apiBase: null
                      maxNetworkRetries: null
                    }
                    #_originalValues: array:2 [
                      "billing_cycle" => "monthly"
                      "plan_id" => "2"
                    ]
                    #_values: array:2 [
                      "billing_cycle" => "monthly"
                      "plan_id" => "2"
                    ]
                    #_unsavedValues: 
Stripe\Util
\
Set
 {#1838
                      -_elts: []
                    }
                    #_transientValues: 
Stripe\Util
\
Set
 {#1839
                      -_elts: []
                    }
                    #_retrieveOptions: []
                    #_lastResponse: null
                    billing_cycle: "monthly"
                    plan_id: "2"
                  }
                  "nickname" => null
                  "product" => "prod_TZpucAeinrzdzH"
                  "recurring" => 
Stripe
\
StripeObject
 {#1837
                    #_opts: 
Stripe\Util
\
RequestOptions
 {#1844
                      +headers: []
                      +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                      +apiBase: null
                      +maxNetworkRetries: null
                      apiKey: "sk_test_***********************************************************************************************5gJx"
                      headers: []
                      apiBase: null
                      maxNetworkRetries: null
                    }
                    #_originalValues: array:5 [
                      "interval" => "month"
                      "interval_count" => 1
                      "meter" => null
                      "trial_period_days" => null
                      "usage_type" => "licensed"
                    ]
                    #_values: array:5 [
                      "interval" => "month"
                      "interval_count" => 1
                      "meter" => null
                      "trial_period_days" => null
                      "usage_type" => "licensed"
                    ]
                    #_unsavedValues: 
Stripe\Util
\
Set
 {#1842
                      -_elts: []
                    }
                    #_transientValues: 
Stripe\Util
\
Set
 {#1843
                      -_elts: []
                    }
                    #_retrieveOptions: []
                    #_lastResponse: null
                    interval: "month"
                    interval_count: 1
                    meter: null
                    trial_period_days: null
                    usage_type: "licensed"
                  }
                  "tax_behavior" => "unspecified"
                  "tiers_mode" => null
                  "transform_quantity" => null
                  "type" => "recurring"
                  "unit_amount" => 2500
                  "unit_amount_decimal" => "2500"
                ]
                #_unsavedValues: 
Stripe\Util
\
Set
 {#1833
                  -_elts: []
                }
                #_transientValues: 
Stripe\Util
\
Set
 {#1834
                  -_elts: []
                }
                #_retrieveOptions: []
                #_lastResponse: null
                +saveWithParent: false
                id: "price_1ScgEnISIl8QzoFfPzV32FUq"
                object: "price"
                active: true
                billing_scheme: "per_unit"
                created: 1765345617
                currency: "usd"
                custom_unit_amount: null
                livemode: false
                lookup_key: null
                metadata: 
Stripe
\
StripeObject
 {#1836}
                nickname: null
                product: "prod_TZpucAeinrzdzH"
                recurring: 
Stripe
\
StripeObject
 {#1837}
                tax_behavior: "unspecified"
                tiers_mode: null
                transform_quantity: null
                type: "recurring"
                unit_amount: 2500
                unit_amount_decimal: "2500"
              }
              "quantity" => 1
              "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
              "tax_rates" => []
            ]
            #_unsavedValues: 
Stripe\Util
\
Set
 {#1816
              -_elts: []
            }
            #_transientValues: 
Stripe\Util
\
Set
 {#1817
              -_elts: []
            }
            #_retrieveOptions: []
            #_lastResponse: null
            +saveWithParent: false
            id: "si_TZpuwoMh1mYQmp"
            object: "subscription_item"
            billing_thresholds: null
            created: 1765345618
            current_period_end: 1768024018
            current_period_start: 1765345618
            discounts: []
            metadata: 
Stripe
\
StripeObject
 {#1819}
            plan: 
Stripe
\
Plan
 {#1820}
            price: 
Stripe
\
Price
 {#1824}
            quantity: 1
            subscription: "sub_1ScgEoISIl8QzoFfGbttNG7T"
            tax_rates: []
          }
        ]
        "has_more" => false
        "total_count" => 1
        "url" => "/v1/subscription_items?subscription=sub_1ScgEoISIl8QzoFfGbttNG7T"
      ]
      #_unsavedValues: 
Stripe\Util
\
Set
 {#1811
        -_elts: []
      }
      #_transientValues: 
Stripe\Util
\
Set
 {#1812
        -_elts: []
      }
      #_retrieveOptions: []
      #_lastResponse: null
      #filters: []
      object: "list"
      data: array:1 [
        0 => 
Stripe
\
SubscriptionItem
 {#1814}
      ]
      has_more: false
      total_count: 1
      url: "/v1/subscription_items?subscription=sub_1ScgEoISIl8QzoFfGbttNG7T"
    }
    "latest_invoice" => 
Stripe
\
Invoice
 {#1748
      #_opts: 
Stripe\Util
\
RequestOptions
 {#1845
        +headers: []
        +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
        +apiBase: null
        +maxNetworkRetries: null
        apiKey: "sk_test_***********************************************************************************************5gJx"
        headers: []
        apiBase: null
        maxNetworkRetries: null
      }
      #_originalValues: array:74 [
        "id" => "in_1ScgEoISIl8QzoFfjn5myIdr"
        "object" => "invoice"
        "account_country" => "AT"
        "account_name" => "Soundcloud "
        "account_tax_ids" => null
        "amount_due" => 2500
        "amount_overpaid" => 0
        "amount_paid" => 0
        "amount_remaining" => 2500
        "amount_shipping" => 0
        "application" => null
        "attempt_count" => 0
        "attempted" => false
        "auto_advance" => false
        "automatic_tax" => array:5 [
          "disabled_reason" => null
          "enabled" => false
          "liability" => null
          "provider" => null
          "status" => null
        ]
        "automatically_finalizes_at" => null
        "billing_reason" => "subscription_create"
        "collection_method" => "charge_automatically"
        "created" => 1765345618
        "currency" => "usd"
        "custom_fields" => null
        "customer" => "cus_TZpBbThDxNdUzj"
        "customer_account" => null
        "customer_address" => null
        "customer_email" => "zijeraw@mailinator.com"
        "customer_name" => "Scarlett Richard"
        "customer_phone" => null
        "customer_shipping" => null
        "customer_tax_exempt" => "none"
        "customer_tax_ids" => []
        "default_payment_method" => null
        "default_source" => null
        "default_tax_rates" => []
        "description" => null
        "discounts" => []
        "due_date" => null
        "effective_at" => 1765345618
        "ending_balance" => 0
        "footer" => null
        "from_invoice" => null
        "hosted_invoice_url" => "https://invoice.stripe.com/i/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ?s=ap"
        "invoice_pdf" => "https://pay.stripe.com/invoice/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ/pdf?s=ap"
        "issuer" => array:1 [
          "type" => "self"
        ]
        "last_finalization_error" => null
        "latest_revision" => null
        "lines" => array:5 [
          "object" => "list"
          "data" => array:1 [
            0 => array:17 [
              "id" => "il_1ScgEoISIl8QzoFfQvZ5R3KJ"
              "object" => "line_item"
              "amount" => 2500
              "currency" => "usd"
              "description" => "1 × Premium Plan - Month Plan (at $25.00 / month)"
              "discount_amounts" => []
              "discountable" => true
              "discounts" => []
              "invoice" => "in_1ScgEoISIl8QzoFfjn5myIdr"
              "livemode" => false
              "metadata" => array:4 [
                "order_id" => "7"
                "plan_id" => "2"
                "source_id" => "2"
                "user_urn" => "urn:sc:users:1001"
              ]
              "parent" => array:3 [
                "invoice_item_details" => null
                "subscription_item_details" => array:5 [
                  "invoice_item" => null
                  "proration" => false
                  "proration_details" => array:1 [
                    "credited_items" => null
                  ]
                  "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
                  "subscription_item" => "si_TZpuwoMh1mYQmp"
                ]
                "type" => "subscription_item_details"
              ]
              "period" => array:2 [
                "end" => 1768024018
                "start" => 1765345618
              ]
              "pretax_credit_amounts" => []
              "pricing" => array:3 [
                "price_details" => array:2 [
                  "price" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                  "product" => "prod_TZpucAeinrzdzH"
                ]
                "type" => "price_details"
                "unit_amount_decimal" => "2500"
              ]
              "quantity" => 1
              "taxes" => []
            ]
          ]
          "has_more" => false
          "total_count" => 1
          "url" => "/v1/invoices/in_1ScgEoISIl8QzoFfjn5myIdr/lines"
        ]
        "livemode" => false
        "metadata" => []
        "next_payment_attempt" => null
        "number" => "OKMWK4LU-0003"
        "on_behalf_of" => null
        "parent" => array:3 [
          "quote_details" => null
          "subscription_details" => array:2 [
            "metadata" => array:4 [
              "order_id" => "7"
              "plan_id" => "2"
              "source_id" => "2"
              "user_urn" => "urn:sc:users:1001"
            ]
            "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
          ]
          "type" => "subscription_details"
        ]
        "payment_settings" => array:3 [
          "default_mandate" => null
          "payment_method_options" => null
          "payment_method_types" => null
        ]
        "period_end" => 1765345618
        "period_start" => 1765345618
        "post_payment_credit_notes_amount" => 0
        "pre_payment_credit_notes_amount" => 0
        "receipt_number" => null
        "rendering" => null
        "shipping_cost" => null
        "shipping_details" => null
        "starting_balance" => 0
        "statement_descriptor" => null
        "status" => "open"
        "status_transitions" => array:4 [
          "finalized_at" => 1765345618
          "marked_uncollectible_at" => null
          "paid_at" => null
          "voided_at" => null
        ]
        "subtotal" => 2500
        "subtotal_excluding_tax" => 2500
        "test_clock" => null
        "total" => 2500
        "total_discount_amounts" => []
        "total_excluding_tax" => 2500
        "total_pretax_credit_amounts" => []
        "total_taxes" => []
        "webhooks_delivered_at" => 1765345618
      ]
      #_values: array:74 [
        "id" => "in_1ScgEoISIl8QzoFfjn5myIdr"
        "object" => "invoice"
        "account_country" => "AT"
        "account_name" => "Soundcloud "
        "account_tax_ids" => null
        "amount_due" => 2500
        "amount_overpaid" => 0
        "amount_paid" => 0
        "amount_remaining" => 2500
        "amount_shipping" => 0
        "application" => null
        "attempt_count" => 0
        "attempted" => false
        "auto_advance" => false
        "automatic_tax" => 
Stripe
\
StripeObject
 {#1846
          #_opts: 
Stripe\Util
\
RequestOptions
 {#1850
            +headers: []
            +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
            +apiBase: null
            +maxNetworkRetries: null
            apiKey: "sk_test_***********************************************************************************************5gJx"
            headers: []
            apiBase: null
            maxNetworkRetries: null
          }
          #_originalValues: array:5 [
            "disabled_reason" => null
            "enabled" => false
            "liability" => null
            "provider" => null
            "status" => null
          ]
          #_values: array:5 [
            "disabled_reason" => null
            "enabled" => false
            "liability" => null
            "provider" => null
            "status" => null
          ]
          #_unsavedValues: 
Stripe\Util
\
Set
 {#1848
            -_elts: []
          }
          #_transientValues: 
Stripe\Util
\
Set
 {#1849
            -_elts: []
          }
          #_retrieveOptions: []
          #_lastResponse: null
          disabled_reason: null
          enabled: false
          liability: null
          provider: null
          status: null
        }
        "automatically_finalizes_at" => null
        "billing_reason" => "subscription_create"
        "collection_method" => "charge_automatically"
        "created" => 1765345618
        "currency" => "usd"
        "custom_fields" => null
        "customer" => "cus_TZpBbThDxNdUzj"
        "customer_account" => null
        "customer_address" => null
        "customer_email" => "zijeraw@mailinator.com"
        "customer_name" => "Scarlett Richard"
        "customer_phone" => null
        "customer_shipping" => null
        "customer_tax_exempt" => "none"
        "customer_tax_ids" => []
        "default_payment_method" => null
        "default_source" => null
        "default_tax_rates" => []
        "description" => null
        "discounts" => []
        "due_date" => null
        "effective_at" => 1765345618
        "ending_balance" => 0
        "footer" => null
        "from_invoice" => null
        "hosted_invoice_url" => "https://invoice.stripe.com/i/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ?s=ap"
        "invoice_pdf" => "https://pay.stripe.com/invoice/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ/pdf?s=ap"
        "issuer" => 
Stripe
\
StripeObject
 {#1847
          #_opts: 
Stripe\Util
\
RequestOptions
 {#1854
            +headers: []
            +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
            +apiBase: null
            +maxNetworkRetries: null
            apiKey: "sk_test_***********************************************************************************************5gJx"
            headers: []
            apiBase: null
            maxNetworkRetries: null
          }
          #_originalValues: array:1 [
            "type" => "self"
          ]
          #_values: array:1 [
            "type" => "self"
          ]
          #_unsavedValues: 
Stripe\Util
\
Set
 {#1852
            -_elts: []
          }
          #_transientValues: 
Stripe\Util
\
Set
 {#1853
            -_elts: []
          }
          #_retrieveOptions: []
          #_lastResponse: null
          type: "self"
        }
        "last_finalization_error" => null
        "latest_revision" => null
        "lines" => 
Stripe
\
Collection
 {#1851
          #_opts: 
Stripe\Util
\
RequestOptions
 {#1858
            +headers: []
            +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
            +apiBase: null
            +maxNetworkRetries: null
            apiKey: "sk_test_***********************************************************************************************5gJx"
            headers: []
            apiBase: null
            maxNetworkRetries: null
          }
          #_originalValues: array:5 [
            "object" => "list"
            "data" => array:1 [
              0 => array:17 [
                "id" => "il_1ScgEoISIl8QzoFfQvZ5R3KJ"
                "object" => "line_item"
                "amount" => 2500
                "currency" => "usd"
                "description" => "1 × Premium Plan - Month Plan (at $25.00 / month)"
                "discount_amounts" => []
                "discountable" => true
                "discounts" => []
                "invoice" => "in_1ScgEoISIl8QzoFfjn5myIdr"
                "livemode" => false
                "metadata" => array:4 [
                  "order_id" => "7"
                  "plan_id" => "2"
                  "source_id" => "2"
                  "user_urn" => "urn:sc:users:1001"
                ]
                "parent" => array:3 [
                  "invoice_item_details" => null
                  "subscription_item_details" => array:5 [
                    "invoice_item" => null
                    "proration" => false
                    "proration_details" => array:1 [
                      "credited_items" => null
                    ]
                    "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
                    "subscription_item" => "si_TZpuwoMh1mYQmp"
                  ]
                  "type" => "subscription_item_details"
                ]
                "period" => array:2 [
                  "end" => 1768024018
                  "start" => 1765345618
                ]
                "pretax_credit_amounts" => []
                "pricing" => array:3 [
                  "price_details" => array:2 [
                    "price" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                    "product" => "prod_TZpucAeinrzdzH"
                  ]
                  "type" => "price_details"
                  "unit_amount_decimal" => "2500"
                ]
                "quantity" => 1
                "taxes" => []
              ]
            ]
            "has_more" => false
            "total_count" => 1
            "url" => "/v1/invoices/in_1ScgEoISIl8QzoFfjn5myIdr/lines"
          ]
          #_values: array:5 [
            "object" => "list"
            "data" => array:1 [
              0 => 
Stripe
\
InvoiceLineItem
 {#1859
                #_opts: 
Stripe\Util
\
RequestOptions
 {#1863
                  +headers: []
                  +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                  +apiBase: null
                  +maxNetworkRetries: null
                  apiKey: "sk_test_***********************************************************************************************5gJx"
                  headers: []
                  apiBase: null
                  maxNetworkRetries: null
                }
                #_originalValues: array:17 [
                  "id" => "il_1ScgEoISIl8QzoFfQvZ5R3KJ"
                  "object" => "line_item"
                  "amount" => 2500
                  "currency" => "usd"
                  "description" => "1 × Premium Plan - Month Plan (at $25.00 / month)"
                  "discount_amounts" => []
                  "discountable" => true
                  "discounts" => []
                  "invoice" => "in_1ScgEoISIl8QzoFfjn5myIdr"
                  "livemode" => false
                  "metadata" => array:4 [
                    "order_id" => "7"
                    "plan_id" => "2"
                    "source_id" => "2"
                    "user_urn" => "urn:sc:users:1001"
                  ]
                  "parent" => array:3 [
                    "invoice_item_details" => null
                    "subscription_item_details" => array:5 [
                      "invoice_item" => null
                      "proration" => false
                      "proration_details" => array:1 [
                        "credited_items" => null
                      ]
                      "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
                      "subscription_item" => "si_TZpuwoMh1mYQmp"
                    ]
                    "type" => "subscription_item_details"
                  ]
                  "period" => array:2 [
                    "end" => 1768024018
                    "start" => 1765345618
                  ]
                  "pretax_credit_amounts" => []
                  "pricing" => array:3 [
                    "price_details" => array:2 [
                      "price" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                      "product" => "prod_TZpucAeinrzdzH"
                    ]
                    "type" => "price_details"
                    "unit_amount_decimal" => "2500"
                  ]
                  "quantity" => 1
                  "taxes" => []
                ]
                #_values: array:17 [
                  "id" => "il_1ScgEoISIl8QzoFfQvZ5R3KJ"
                  "object" => "line_item"
                  "amount" => 2500
                  "currency" => "usd"
                  "description" => "1 × Premium Plan - Month Plan (at $25.00 / month)"
                  "discount_amounts" => []
                  "discountable" => true
                  "discounts" => []
                  "invoice" => "in_1ScgEoISIl8QzoFfjn5myIdr"
                  "livemode" => false
                  "metadata" => 
Stripe
\
StripeObject
 {#1864
                    #_opts: 
Stripe\Util
\
RequestOptions
 {#1868
                      +headers: []
                      +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                      +apiBase: null
                      +maxNetworkRetries: null
                      apiKey: "sk_test_***********************************************************************************************5gJx"
                      headers: []
                      apiBase: null
                      maxNetworkRetries: null
                    }
                    #_originalValues: array:4 [
                      "order_id" => "7"
                      "plan_id" => "2"
                      "source_id" => "2"
                      "user_urn" => "urn:sc:users:1001"
                    ]
                    #_values: array:4 [
                      "order_id" => "7"
                      "plan_id" => "2"
                      "source_id" => "2"
                      "user_urn" => "urn:sc:users:1001"
                    ]
                    #_unsavedValues: 
Stripe\Util
\
Set
 {#1866
                      -_elts: []
                    }
                    #_transientValues: 
Stripe\Util
\
Set
 {#1867
                      -_elts: []
                    }
                    #_retrieveOptions: []
                    #_lastResponse: null
                    order_id: "7"
                    plan_id: "2"
                    source_id: "2"
                    user_urn: "urn:sc:users:1001"
                  }
                  "parent" => 
Stripe
\
StripeObject
 {#1865
                    #_opts: 
Stripe\Util
\
RequestOptions
 {#1872
                      +headers: []
                      +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                      +apiBase: null
                      +maxNetworkRetries: null
                      apiKey: "sk_test_***********************************************************************************************5gJx"
                      headers: []
                      apiBase: null
                      maxNetworkRetries: null
                    }
                    #_originalValues: array:3 [
                      "invoice_item_details" => null
                      "subscription_item_details" => array:5 [
                        "invoice_item" => null
                        "proration" => false
                        "proration_details" => array:1 [
                          "credited_items" => null
                        ]
                        "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
                        "subscription_item" => "si_TZpuwoMh1mYQmp"
                      ]
                      "type" => "subscription_item_details"
                    ]
                    #_values: array:3 [
                      "invoice_item_details" => null
                      "subscription_item_details" => 
Stripe
\
StripeObject
 {#1873
                        #_opts: 
Stripe\Util
\
RequestOptions
 {#1877
                          +headers: []
                          +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                          +apiBase: null
                          +maxNetworkRetries: null
                          apiKey: "sk_test_***********************************************************************************************5gJx"
                          headers: []
                          apiBase: null
                          maxNetworkRetries: null
                        }
                        #_originalValues: array:5 [
                          "invoice_item" => null
                          "proration" => false
                          "proration_details" => array:1 [
                            "credited_items" => null
                          ]
                          "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
                          "subscription_item" => "si_TZpuwoMh1mYQmp"
                        ]
                        #_values: array:5 [
                          "invoice_item" => null
                          "proration" => false
                          "proration_details" => 
Stripe
\
StripeObject
 {#1878
                            #_opts: 
Stripe\Util
\
RequestOptions
 {#1882
                              +headers: []
                              +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                              +apiBase: null
                              +maxNetworkRetries: null
                              apiKey: "sk_test_***********************************************************************************************5gJx"
                              headers: []
                              apiBase: null
                              maxNetworkRetries: null
                            }
                            #_originalValues: array:1 [
                              "credited_items" => null
                            ]
                            #_values: array:1 [
                              "credited_items" => null
                            ]
                            #_unsavedValues: 
Stripe\Util
\
Set
 {#1880
                              -_elts: []
                            }
                            #_transientValues: 
Stripe\Util
\
Set
 {#1881
                              -_elts: []
                            }
                            #_retrieveOptions: []
                            #_lastResponse: null
                            credited_items: null
                          }
                          "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
                          "subscription_item" => "si_TZpuwoMh1mYQmp"
                        ]
                        #_unsavedValues: 
Stripe\Util
\
Set
 {#1875
                          -_elts: []
                        }
                        #_transientValues: 
Stripe\Util
\
Set
 {#1876
                          -_elts: []
                        }
                        #_retrieveOptions: []
                        #_lastResponse: null
                        invoice_item: null
                        proration: false
                        proration_details: 
Stripe
\
StripeObject
 {#1878}
                        subscription: "sub_1ScgEoISIl8QzoFfGbttNG7T"
                        subscription_item: "si_TZpuwoMh1mYQmp"
                      }
                      "type" => "subscription_item_details"
                    ]
                    #_unsavedValues: 
Stripe\Util
\
Set
 {#1870
                      -_elts: []
                    }
                    #_transientValues: 
Stripe\Util
\
Set
 {#1871
                      -_elts: []
                    }
                    #_retrieveOptions: []
                    #_lastResponse: null
                    invoice_item_details: null
                    subscription_item_details: 
Stripe
\
StripeObject
 {#1873}
                    type: "subscription_item_details"
                  }
                  "period" => 
Stripe
\
StripeObject
 {#1869
                    #_opts: 
Stripe\Util
\
RequestOptions
 {#1884
                      +headers: []
                      +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                      +apiBase: null
                      +maxNetworkRetries: null
                      apiKey: "sk_test_***********************************************************************************************5gJx"
                      headers: []
                      apiBase: null
                      maxNetworkRetries: null
                    }
                    #_originalValues: array:2 [
                      "end" => 1768024018
                      "start" => 1765345618
                    ]
                    #_values: array:2 [
                      "end" => 1768024018
                      "start" => 1765345618
                    ]
                    #_unsavedValues: 
Stripe\Util
\
Set
 {#1879
                      -_elts: []
                    }
                    #_transientValues: 
Stripe\Util
\
Set
 {#1883
                      -_elts: []
                    }
                    #_retrieveOptions: []
                    #_lastResponse: null
                    end: 1768024018
                    start: 1765345618
                  }
                  "pretax_credit_amounts" => []
                  "pricing" => 
Stripe
\
StripeObject
 {#1874
                    #_opts: 
Stripe\Util
\
RequestOptions
 {#1888
                      +headers: []
                      +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                      +apiBase: null
                      +maxNetworkRetries: null
                      apiKey: "sk_test_***********************************************************************************************5gJx"
                      headers: []
                      apiBase: null
                      maxNetworkRetries: null
                    }
                    #_originalValues: array:3 [
                      "price_details" => array:2 [
                        "price" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                        "product" => "prod_TZpucAeinrzdzH"
                      ]
                      "type" => "price_details"
                      "unit_amount_decimal" => "2500"
                    ]
                    #_values: array:3 [
                      "price_details" => 
Stripe
\
StripeObject
 {#1889
                        #_opts: 
Stripe\Util
\
RequestOptions
 {#1893
                          +headers: []
                          +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                          +apiBase: null
                          +maxNetworkRetries: null
                          apiKey: "sk_test_***********************************************************************************************5gJx"
                          headers: []
                          apiBase: null
                          maxNetworkRetries: null
                        }
                        #_originalValues: array:2 [
                          "price" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                          "product" => "prod_TZpucAeinrzdzH"
                        ]
                        #_values: array:2 [
                          "price" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                          "product" => "prod_TZpucAeinrzdzH"
                        ]
                        #_unsavedValues: 
Stripe\Util
\
Set
 {#1891
                          -_elts: []
                        }
                        #_transientValues: 
Stripe\Util
\
Set
 {#1892
                          -_elts: []
                        }
                        #_retrieveOptions: []
                        #_lastResponse: null
                        price: "price_1ScgEnISIl8QzoFfPzV32FUq"
                        product: "prod_TZpucAeinrzdzH"
                      }
                      "type" => "price_details"
                      "unit_amount_decimal" => "2500"
                    ]
                    #_unsavedValues: 
Stripe\Util
\
Set
 {#1886
                      -_elts: []
                    }
                    #_transientValues: 
Stripe\Util
\
Set
 {#1887
                      -_elts: []
                    }
                    #_retrieveOptions: []
                    #_lastResponse: null
                    price_details: 
Stripe
\
StripeObject
 {#1889}
                    type: "price_details"
                    unit_amount_decimal: "2500"
                  }
                  "quantity" => 1
                  "taxes" => []
                ]
                #_unsavedValues: 
Stripe\Util
\
Set
 {#1861
                  -_elts: []
                }
                #_transientValues: 
Stripe\Util
\
Set
 {#1862
                  -_elts: []
                }
                #_retrieveOptions: []
                #_lastResponse: null
                +saveWithParent: false
                id: "il_1ScgEoISIl8QzoFfQvZ5R3KJ"
                object: "line_item"
                amount: 2500
                currency: "usd"
                description: "1 × Premium Plan - Month Plan (at $25.00 / month)"
                discount_amounts: []
                discountable: true
                discounts: []
                invoice: "in_1ScgEoISIl8QzoFfjn5myIdr"
                livemode: false
                metadata: 
Stripe
\
StripeObject
 {#1864}
                parent: 
Stripe
\
StripeObject
 {#1865}
                period: 
Stripe
\
StripeObject
 {#1869}
                pretax_credit_amounts: []
                pricing: 
Stripe
\
StripeObject
 {#1874}
                quantity: 1
                taxes: []
              }
            ]
            "has_more" => false
            "total_count" => 1
            "url" => "/v1/invoices/in_1ScgEoISIl8QzoFfjn5myIdr/lines"
          ]
          #_unsavedValues: 
Stripe\Util
\
Set
 {#1856
            -_elts: []
          }
          #_transientValues: 
Stripe\Util
\
Set
 {#1857
            -_elts: []
          }
          #_retrieveOptions: []
          #_lastResponse: null
          #filters: []
          object: "list"
          data: array:1 [
            0 => 
Stripe
\
InvoiceLineItem
 {#1859}
          ]
          has_more: false
          total_count: 1
          url: "/v1/invoices/in_1ScgEoISIl8QzoFfjn5myIdr/lines"
        }
        "livemode" => false
        "metadata" => 
Stripe
\
StripeObject
 {#1855
          #_opts: 
Stripe\Util
\
RequestOptions
 {#1894
            +headers: []
            +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
            +apiBase: null
            +maxNetworkRetries: null
            apiKey: "sk_test_***********************************************************************************************5gJx"
            headers: []
            apiBase: null
            maxNetworkRetries: null
          }
          #_originalValues: []
          #_values: []
          #_unsavedValues: 
Stripe\Util
\
Set
 {#1885
            -_elts: []
          }
          #_transientValues: 
Stripe\Util
\
Set
 {#1890
            -_elts: []
          }
          #_retrieveOptions: []
          #_lastResponse: null
        }
        "next_payment_attempt" => null
        "number" => "OKMWK4LU-0003"
        "on_behalf_of" => null
        "parent" => 
Stripe
\
StripeObject
 {#1860
          #_opts: 
Stripe\Util
\
RequestOptions
 {#1898
            +headers: []
            +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
            +apiBase: null
            +maxNetworkRetries: null
            apiKey: "sk_test_***********************************************************************************************5gJx"
            headers: []
            apiBase: null
            maxNetworkRetries: null
          }
          #_originalValues: array:3 [
            "quote_details" => null
            "subscription_details" => array:2 [
              "metadata" => array:4 [
                "order_id" => "7"
                "plan_id" => "2"
                "source_id" => "2"
                "user_urn" => "urn:sc:users:1001"
              ]
              "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
            ]
            "type" => "subscription_details"
          ]
          #_values: array:3 [
            "quote_details" => null
            "subscription_details" => 
Stripe
\
StripeObject
 {#1899
              #_opts: 
Stripe\Util
\
RequestOptions
 {#1903
                +headers: []
                +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                +apiBase: null
                +maxNetworkRetries: null
                apiKey: "sk_test_***********************************************************************************************5gJx"
                headers: []
                apiBase: null
                maxNetworkRetries: null
              }
              #_originalValues: array:2 [
                "metadata" => array:4 [
                  "order_id" => "7"
                  "plan_id" => "2"
                  "source_id" => "2"
                  "user_urn" => "urn:sc:users:1001"
                ]
                "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
              ]
              #_values: array:2 [
                "metadata" => 
Stripe
\
StripeObject
 {#1904
                  #_opts: 
Stripe\Util
\
RequestOptions
 {#1908
                    +headers: []
                    +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
                    +apiBase: null
                    +maxNetworkRetries: null
                    apiKey: "sk_test_***********************************************************************************************5gJx"
                    headers: []
                    apiBase: null
                    maxNetworkRetries: null
                  }
                  #_originalValues: array:4 [
                    "order_id" => "7"
                    "plan_id" => "2"
                    "source_id" => "2"
                    "user_urn" => "urn:sc:users:1001"
                  ]
                  #_values: array:4 [
                    "order_id" => "7"
                    "plan_id" => "2"
                    "source_id" => "2"
                    "user_urn" => "urn:sc:users:1001"
                  ]
                  #_unsavedValues: 
Stripe\Util
\
Set
 {#1906
                    -_elts: []
                  }
                  #_transientValues: 
Stripe\Util
\
Set
 {#1907
                    -_elts: []
                  }
                  #_retrieveOptions: []
                  #_lastResponse: null
                  order_id: "7"
                  plan_id: "2"
                  source_id: "2"
                  user_urn: "urn:sc:users:1001"
                }
                "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
              ]
              #_unsavedValues: 
Stripe\Util
\
Set
 {#1901
                -_elts: []
              }
              #_transientValues: 
Stripe\Util
\
Set
 {#1902
                -_elts: []
              }
              #_retrieveOptions: []
              #_lastResponse: null
              metadata: 
Stripe
\
StripeObject
 {#1904}
              subscription: "sub_1ScgEoISIl8QzoFfGbttNG7T"
            }
            "type" => "subscription_details"
          ]
          #_unsavedValues: 
Stripe\Util
\
Set
 {#1896
            -_elts: []
          }
          #_transientValues: 
Stripe\Util
\
Set
 {#1897
            -_elts: []
          }
          #_retrieveOptions: []
          #_lastResponse: null
          quote_details: null
          subscription_details: 
Stripe
\
StripeObject
 {#1899}
          type: "subscription_details"
        }
        "payment_settings" => 
Stripe
\
StripeObject
 {#1895
          #_opts: 
Stripe\Util
\
RequestOptions
 {#1910
            +headers: []
            +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
            +apiBase: null
            +maxNetworkRetries: null
            apiKey: "sk_test_***********************************************************************************************5gJx"
            headers: []
            apiBase: null
            maxNetworkRetries: null
          }
          #_originalValues: array:3 [
            "default_mandate" => null
            "payment_method_options" => null
            "payment_method_types" => null
          ]
          #_values: array:3 [
            "default_mandate" => null
            "payment_method_options" => null
            "payment_method_types" => null
          ]
          #_unsavedValues: 
Stripe\Util
\
Set
 {#1905
            -_elts: []
          }
          #_transientValues: 
Stripe\Util
\
Set
 {#1909
            -_elts: []
          }
          #_retrieveOptions: []
          #_lastResponse: null
          default_mandate: null
          payment_method_options: null
          payment_method_types: null
        }
        "period_end" => 1765345618
        "period_start" => 1765345618
        "post_payment_credit_notes_amount" => 0
        "pre_payment_credit_notes_amount" => 0
        "receipt_number" => null
        "rendering" => null
        "shipping_cost" => null
        "shipping_details" => null
        "starting_balance" => 0
        "statement_descriptor" => null
        "status" => "open"
        "status_transitions" => 
Stripe
\
StripeObject
 {#1900
          #_opts: 
Stripe\Util
\
RequestOptions
 {#1914
            +headers: []
            +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
            +apiBase: null
            +maxNetworkRetries: null
            apiKey: "sk_test_***********************************************************************************************5gJx"
            headers: []
            apiBase: null
            maxNetworkRetries: null
          }
          #_originalValues: array:4 [
            "finalized_at" => 1765345618
            "marked_uncollectible_at" => null
            "paid_at" => null
            "voided_at" => null
          ]
          #_values: array:4 [
            "finalized_at" => 1765345618
            "marked_uncollectible_at" => null
            "paid_at" => null
            "voided_at" => null
          ]
          #_unsavedValues: 
Stripe\Util
\
Set
 {#1912
            -_elts: []
          }
          #_transientValues: 
Stripe\Util
\
Set
 {#1913
            -_elts: []
          }
          #_retrieveOptions: []
          #_lastResponse: null
          finalized_at: 1765345618
          marked_uncollectible_at: null
          paid_at: null
          voided_at: null
        }
        "subtotal" => 2500
        "subtotal_excluding_tax" => 2500
        "test_clock" => null
        "total" => 2500
        "total_discount_amounts" => []
        "total_excluding_tax" => 2500
        "total_pretax_credit_amounts" => []
        "total_taxes" => []
        "webhooks_delivered_at" => 1765345618
      ]
      #_unsavedValues: 
Stripe\Util
\
Set
 {#1829
        -_elts: []
      }
      #_transientValues: 
Stripe\Util
\
Set
 {#1841
        -_elts: []
      }
      #_retrieveOptions: []
      #_lastResponse: null
      +saveWithParent: false
      id: "in_1ScgEoISIl8QzoFfjn5myIdr"
      object: "invoice"
      account_country: "AT"
      account_name: "Soundcloud "
      account_tax_ids: null
      amount_due: 2500
      amount_overpaid: 0
      amount_paid: 0
      amount_remaining: 2500
      amount_shipping: 0
      application: null
      attempt_count: 0
      attempted: false
      auto_advance: false
      automatic_tax: 
Stripe
\
StripeObject
 {#1846}
      automatically_finalizes_at: null
      billing_reason: "subscription_create"
      collection_method: "charge_automatically"
      created: 1765345618
      currency: "usd"
      custom_fields: null
      customer: "cus_TZpBbThDxNdUzj"
      customer_account: null
      customer_address: null
      customer_email: "zijeraw@mailinator.com"
      customer_name: "Scarlett Richard"
      customer_phone: null
      customer_shipping: null
      customer_tax_exempt: "none"
      customer_tax_ids: []
      default_payment_method: null
      default_source: null
      default_tax_rates: []
      description: null
      discounts: []
      due_date: null
      effective_at: 1765345618
      ending_balance: 0
      footer: null
      from_invoice: null
      hosted_invoice_url: "https://invoice.stripe.com/i/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ?s=ap"
      invoice_pdf: "https://pay.stripe.com/invoice/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ/pdf?s=ap"
      issuer: 
Stripe
\
StripeObject
 {#1847}
      last_finalization_error: null
      latest_revision: null
      lines: 
Stripe
\
Collection
 {#1851}
      livemode: false
      metadata: 
Stripe
\
StripeObject
 {#1855}
      next_payment_attempt: null
      number: "OKMWK4LU-0003"
      on_behalf_of: null
      parent: 
Stripe
\
StripeObject
 {#1860}
      payment_settings: 
Stripe
\
StripeObject
 {#1895}
      period_end: 1765345618
      period_start: 1765345618
      post_payment_credit_notes_amount: 0
      pre_payment_credit_notes_amount: 0
      receipt_number: null
      rendering: null
      shipping_cost: null
      shipping_details: null
      starting_balance: 0
      statement_descriptor: null
      status: "open"
      status_transitions: 
Stripe
\
StripeObject
 {#1900}
      subtotal: 2500
      subtotal_excluding_tax: 2500
      test_clock: null
      total: 2500
      total_discount_amounts: []
      total_excluding_tax: 2500
      total_pretax_credit_amounts: []
      total_taxes: []
      webhooks_delivered_at: 1765345618
    }
    "livemode" => false
    "metadata" => 
Stripe
\
StripeObject
 {#1815
      #_opts: 
Stripe\Util
\
RequestOptions
 {#1917
        +headers: []
        +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
        +apiBase: null
        +maxNetworkRetries: null
        apiKey: "sk_test_***********************************************************************************************5gJx"
        headers: []
        apiBase: null
        maxNetworkRetries: null
      }
      #_originalValues: array:4 [
        "order_id" => "7"
        "plan_id" => "2"
        "source_id" => "2"
        "user_urn" => "urn:sc:users:1001"
      ]
      #_values: array:4 [
        "order_id" => "7"
        "plan_id" => "2"
        "source_id" => "2"
        "user_urn" => "urn:sc:users:1001"
      ]
      #_unsavedValues: 
Stripe\Util
\
Set
 {#1915
        -_elts: []
      }
      #_transientValues: 
Stripe\Util
\
Set
 {#1916
        -_elts: []
      }
      #_retrieveOptions: []
      #_lastResponse: null
      order_id: "7"
      plan_id: "2"
      source_id: "2"
      user_urn: "urn:sc:users:1001"
    }
    "next_pending_invoice_item_invoice" => null
    "on_behalf_of" => null
    "pause_collection" => null
    "payment_settings" => 
Stripe
\
StripeObject
 {#1911
      #_opts: 
Stripe\Util
\
RequestOptions
 {#1921
        +headers: []
        +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
        +apiBase: null
        +maxNetworkRetries: null
        apiKey: "sk_test_***********************************************************************************************5gJx"
        headers: []
        apiBase: null
        maxNetworkRetries: null
      }
      #_originalValues: array:3 [
        "payment_method_options" => null
        "payment_method_types" => null
        "save_default_payment_method" => "on_subscription"
      ]
      #_values: array:3 [
        "payment_method_options" => null
        "payment_method_types" => null
        "save_default_payment_method" => "on_subscription"
      ]
      #_unsavedValues: 
Stripe\Util
\
Set
 {#1919
        -_elts: []
      }
      #_transientValues: 
Stripe\Util
\
Set
 {#1920
        -_elts: []
      }
      #_retrieveOptions: []
      #_lastResponse: null
      payment_method_options: null
      payment_method_types: null
      save_default_payment_method: "on_subscription"
    }
    "pending_invoice_item_interval" => null
    "pending_setup_intent" => null
    "pending_update" => null
    "plan" => 
Stripe
\
Plan
 {#1918
      #_opts: 
Stripe\Util
\
RequestOptions
 {#1925
        +headers: []
        +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
        +apiBase: null
        +maxNetworkRetries: null
        apiKey: "sk_test_***********************************************************************************************5gJx"
        headers: []
        apiBase: null
        maxNetworkRetries: null
      }
      #_originalValues: array:19 [
        "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
        "object" => "plan"
        "active" => true
        "amount" => 2500
        "amount_decimal" => "2500"
        "billing_scheme" => "per_unit"
        "created" => 1765345617
        "currency" => "usd"
        "interval" => "month"
        "interval_count" => 1
        "livemode" => false
        "metadata" => array:2 [
          "billing_cycle" => "monthly"
          "plan_id" => "2"
        ]
        "meter" => null
        "nickname" => null
        "product" => "prod_TZpucAeinrzdzH"
        "tiers_mode" => null
        "transform_usage" => null
        "trial_period_days" => null
        "usage_type" => "licensed"
      ]
      #_values: array:19 [
        "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
        "object" => "plan"
        "active" => true
        "amount" => 2500
        "amount_decimal" => "2500"
        "billing_scheme" => "per_unit"
        "created" => 1765345617
        "currency" => "usd"
        "interval" => "month"
        "interval_count" => 1
        "livemode" => false
        "metadata" => 
Stripe
\
StripeObject
 {#1926
          #_opts: 
Stripe\Util
\
RequestOptions
 {#1930
            +headers: []
            +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
            +apiBase: null
            +maxNetworkRetries: null
            apiKey: "sk_test_***********************************************************************************************5gJx"
            headers: []
            apiBase: null
            maxNetworkRetries: null
          }
          #_originalValues: array:2 [
            "billing_cycle" => "monthly"
            "plan_id" => "2"
          ]
          #_values: array:2 [
            "billing_cycle" => "monthly"
            "plan_id" => "2"
          ]
          #_unsavedValues: 
Stripe\Util
\
Set
 {#1928
            -_elts: []
          }
          #_transientValues: 
Stripe\Util
\
Set
 {#1929
            -_elts: []
          }
          #_retrieveOptions: []
          #_lastResponse: null
          billing_cycle: "monthly"
          plan_id: "2"
        }
        "meter" => null
        "nickname" => null
        "product" => "prod_TZpucAeinrzdzH"
        "tiers_mode" => null
        "transform_usage" => null
        "trial_period_days" => null
        "usage_type" => "licensed"
      ]
      #_unsavedValues: 
Stripe\Util
\
Set
 {#1923
        -_elts: []
      }
      #_transientValues: 
Stripe\Util
\
Set
 {#1924
        -_elts: []
      }
      #_retrieveOptions: []
      #_lastResponse: null
      +saveWithParent: false
      id: "price_1ScgEnISIl8QzoFfPzV32FUq"
      object: "plan"
      active: true
      amount: 2500
      amount_decimal: "2500"
      billing_scheme: "per_unit"
      created: 1765345617
      currency: "usd"
      interval: "month"
      interval_count: 1
      livemode: false
      metadata: 
Stripe
\
StripeObject
 {#1926}
      meter: null
      nickname: null
      product: "prod_TZpucAeinrzdzH"
      tiers_mode: null
      transform_usage: null
      trial_period_days: null
      usage_type: "licensed"
    }
    "quantity" => 1
    "schedule" => null
    "start_date" => 1765345618
    "status" => "incomplete"
    "test_clock" => null
    "transfer_data" => null
    "trial_end" => null
    "trial_settings" => 
Stripe
\
StripeObject
 {#1922
      #_opts: 
Stripe\Util
\
RequestOptions
 {#1933
        +headers: []
        +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
        +apiBase: null
        +maxNetworkRetries: null
        apiKey: "sk_test_***********************************************************************************************5gJx"
        headers: []
        apiBase: null
        maxNetworkRetries: null
      }
      #_originalValues: array:1 [
        "end_behavior" => array:1 [
          "missing_payment_method" => "create_invoice"
        ]
      ]
      #_values: array:1 [
        "end_behavior" => 
Stripe
\
StripeObject
 {#1934
          #_opts: 
Stripe\Util
\
RequestOptions
 {#1938
            +headers: []
            +apiKey: "sk_test_51RpQw7ISIl8QzoFf8r7wCZtb1Oauyo05c4kf7eLfoET3edbVwHVoBH4UeViPTii9V5GfosbnlYInMS1CArx77fMS00zK935gJx"
            +apiBase: null
            +maxNetworkRetries: null
            apiKey: "sk_test_***********************************************************************************************5gJx"
            headers: []
            apiBase: null
            maxNetworkRetries: null
          }
          #_originalValues: array:1 [
            "missing_payment_method" => "create_invoice"
          ]
          #_values: array:1 [
            "missing_payment_method" => "create_invoice"
          ]
          #_unsavedValues: 
Stripe\Util
\
Set
 {#1936
            -_elts: []
          }
          #_transientValues: 
Stripe\Util
\
Set
 {#1937
            -_elts: []
          }
          #_retrieveOptions: []
          #_lastResponse: null
          missing_payment_method: "create_invoice"
        }
      ]
      #_unsavedValues: 
Stripe\Util
\
Set
 {#1931
        -_elts: []
      }
      #_transientValues: 
Stripe\Util
\
Set
 {#1932
        -_elts: []
      }
      #_retrieveOptions: []
      #_lastResponse: null
      end_behavior: 
Stripe
\
StripeObject
 {#1934}
    }
    "trial_start" => null
  ]
  #_unsavedValues: 
Stripe\Util
\
Set
 {#1776
    -_elts: []
  }
  #_transientValues: 
Stripe\Util
\
Set
 {#1780
    -_elts: []
  }
  #_retrieveOptions: []
  #_lastResponse: 
Stripe
\
ApiResponse
 {#2261
    +headers: 
Stripe\Util
\
CaseInsensitiveArray
 {#1764
      -container: array:21 [
        "server" => "nginx"
        "date" => "Wed, 10 Dec 2025 05:46:59 GMT"
        "content-type" => "application/json"
        "content-length" => "9483"
        "access-control-allow-credentials" => "true"
        "access-control-allow-methods" => "GET, HEAD, PUT, PATCH, POST, DELETE"
        "access-control-allow-origin" => "*"
        "access-control-expose-headers" => "Request-Id, Stripe-Manage-Version, Stripe-Should-Retry, X-Stripe-External-Auth-Required, X-Stripe-Privileged-Session-Required"
        "access-control-max-age" => "300"
        "cache-control" => "no-cache, no-store"
        "content-security-policy" => "base-uri 'none'; default-src 'none'; form-action 'none'; frame-ancestors 'none'; img-src 'self'; script-src 'self' 'report-sample'; style-src 'self'; worker-src 'none'; upgrade-insecure-requests; report-uri https://q.stripe.com/csp-violation?q=ISeptywRoOE9spil-ScEHM00S13rfpjCcP0K2Llz_i5_g3xX79mWXBPj8X_zei_gfIXMsLDWTjJD1RTo"
        "idempotency-key" => "40e5a851-cb77-451a-a605-29fb4cd34ff0"
        "original-request" => "req_hFOf4H30q2JUti"
        "request-id" => "req_hFOf4H30q2JUti"
        "stripe-should-retry" => "false"
        "stripe-version" => "2025-08-27.basil"
        "vary" => "Origin"
        "x-stripe-priority-routing-enabled" => "true"
        "x-stripe-routing-context-priority-tier" => "api-testmode"
        "x-wc" => "ABGHIJ"
        "strict-transport-security" => "max-age=63072000; includeSubDomains; preload"
      ]
    }
    +body: """
      {
        "id": "sub_1ScgEoISIl8QzoFfGbttNG7T",
        "object": "subscription",
        "application": null,
        "application_fee_percent": null,
        "automatic_tax": {
          "disabled_reason": null,
          "enabled": false,
          "liability": null
        },
        "billing_cycle_anchor": 1765345618,
        "billing_cycle_anchor_config": null,
        "billing_mode": {
          "flexible": null,
          "type": "classic"
        },
        "billing_thresholds": null,
        "cancel_at": null,
        "cancel_at_period_end": false,
        "canceled_at": null,
        "cancellation_details": {
          "comment": null,
          "feedback": null,
          "reason": null
        },
        "collection_method": "charge_automatically",
        "created": 1765345618,
        "currency": "usd",
        "customer": "cus_TZpBbThDxNdUzj",
        "customer_account": null,
        "days_until_due": null,
        "default_payment_method": null,
        "default_source": null,
        "default_tax_rates": [],
        "description": null,
        "discounts": [],
        "ended_at": null,
        "invoice_settings": {
          "account_tax_ids": null,
          "issuer": {
            "type": "self"
          }
        },
        "items": {
          "object": "list",
          "data": [
            {
              "id": "si_TZpuwoMh1mYQmp",
              "object": "subscription_item",
              "billing_thresholds": null,
              "created": 1765345618,
              "current_period_end": 1768024018,
              "current_period_start": 1765345618,
              "discounts": [],
              "metadata": {},
              "plan": {
                "id": "price_1ScgEnISIl8QzoFfPzV32FUq",
                "object": "plan",
                "active": true,
                "amount": 2500,
                "amount_decimal": "2500",
                "billing_scheme": "per_unit",
                "created": 1765345617,
                "currency": "usd",
                "interval": "month",
                "interval_count": 1,
                "livemode": false,
                "metadata": {
                  "billing_cycle": "monthly",
                  "plan_id": "2"
                },
                "meter": null,
                "nickname": null,
                "product": "prod_TZpucAeinrzdzH",
                "tiers_mode": null,
                "transform_usage": null,
                "trial_period_days": null,
                "usage_type": "licensed"
              },
              "price": {
                "id": "price_1ScgEnISIl8QzoFfPzV32FUq",
                "object": "price",
                "active": true,
                "billing_scheme": "per_unit",
                "created": 1765345617,
                "currency": "usd",
                "custom_unit_amount": null,
                "livemode": false,
                "lookup_key": null,
                "metadata": {
                  "billing_cycle": "monthly",
                  "plan_id": "2"
                },
                "nickname": null,
                "product": "prod_TZpucAeinrzdzH",
                "recurring": {
                  "interval": "month",
                  "interval_count": 1,
                  "meter": null,
                  "trial_period_days": null,
                  "usage_type": "licensed"
                },
                "tax_behavior": "unspecified",
                "tiers_mode": null,
                "transform_quantity": null,
                "type": "recurring",
                "unit_amount": 2500,
                "unit_amount_decimal": "2500"
              },
              "quantity": 1,
              "subscription": "sub_1ScgEoISIl8QzoFfGbttNG7T",
              "tax_rates": []
            }
          ],
          "has_more": false,
          "total_count": 1,
          "url": "/v1/subscription_items?subscription=sub_1ScgEoISIl8QzoFfGbttNG7T"
        },
        "latest_invoice": {
          "id": "in_1ScgEoISIl8QzoFfjn5myIdr",
          "object": "invoice",
          "account_country": "AT",
          "account_name": "Soundcloud ",
          "account_tax_ids": null,
          "amount_due": 2500,
          "amount_overpaid": 0,
          "amount_paid": 0,
          "amount_remaining": 2500,
          "amount_shipping": 0,
          "application": null,
          "attempt_count": 0,
          "attempted": false,
          "auto_advance": false,
          "automatic_tax": {
            "disabled_reason": null,
            "enabled": false,
            "liability": null,
            "provider": null,
            "status": null
          },
          "automatically_finalizes_at": null,
          "billing_reason": "subscription_create",
          "collection_method": "charge_automatically",
          "created": 1765345618,
          "currency": "usd",
          "custom_fields": null,
          "customer": "cus_TZpBbThDxNdUzj",
          "customer_account": null,
          "customer_address": null,
          "customer_email": "zijeraw@mailinator.com",
          "customer_name": "Scarlett Richard",
          "customer_phone": null,
          "customer_shipping": null,
          "customer_tax_exempt": "none",
          "customer_tax_ids": [],
          "default_payment_method": null,
          "default_source": null,
          "default_tax_rates": [],
          "description": null,
          "discounts": [],
          "due_date": null,
          "effective_at": 1765345618,
          "ending_balance": 0,
          "footer": null,
          "from_invoice": null,
          "hosted_invoice_url": "https://invoice.stripe.com/i/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ?s=ap",
          "invoice_pdf": "https://pay.stripe.com/invoice/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ/pdf?s=ap",
          "issuer": {
            "type": "self"
          },
          "last_finalization_error": null,
          "latest_revision": null,
          "lines": {
            "object": "list",
            "data": [
              {
                "id": "il_1ScgEoISIl8QzoFfQvZ5R3KJ",
                "object": "line_item",
                "amount": 2500,
                "currency": "usd",
                "description": "1 × Premium Plan - Month Plan (at $25.00 / month)",
                "discount_amounts": [],
                "discountable": true,
                "discounts": [],
                "invoice": "in_1ScgEoISIl8QzoFfjn5myIdr",
                "livemode": false,
                "metadata": {
                  "order_id": "7",
                  "plan_id": "2",
                  "source_id": "2",
                  "user_urn": "urn:sc:users:1001"
                },
                "parent": {
                  "invoice_item_details": null,
                  "subscription_item_details": {
                    "invoice_item": null,
                    "proration": false,
                    "proration_details": {
                      "credited_items": null
                    },
                    "subscription": "sub_1ScgEoISIl8QzoFfGbttNG7T",
                    "subscription_item": "si_TZpuwoMh1mYQmp"
                  },
                  "type": "subscription_item_details"
                },
                "period": {
                  "end": 1768024018,
                  "start": 1765345618
                },
                "pretax_credit_amounts": [],
                "pricing": {
                  "price_details": {
                    "price": "price_1ScgEnISIl8QzoFfPzV32FUq",
                    "product": "prod_TZpucAeinrzdzH"
                  },
                  "type": "price_details",
                  "unit_amount_decimal": "2500"
                },
                "quantity": 1,
                "taxes": []
              }
            ],
            "has_more": false,
            "total_count": 1,
            "url": "/v1/invoices/in_1ScgEoISIl8QzoFfjn5myIdr/lines"
          },
          "livemode": false,
          "metadata": {},
          "next_payment_attempt": null,
          "number": "OKMWK4LU-0003",
          "on_behalf_of": null,
          "parent": {
            "quote_details": null,
            "subscription_details": {
              "metadata": {
                "order_id": "7",
                "plan_id": "2",
                "source_id": "2",
                "user_urn": "urn:sc:users:1001"
              },
              "subscription": "sub_1ScgEoISIl8QzoFfGbttNG7T"
            },
            "type": "subscription_details"
          },
          "payment_settings": {
            "default_mandate": null,
            "payment_method_options": null,
            "payment_method_types": null
          },
          "period_end": 1765345618,
          "period_start": 1765345618,
          "post_payment_credit_notes_amount": 0,
          "pre_payment_credit_notes_amount": 0,
          "receipt_number": null,
          "rendering": null,
          "shipping_cost": null,
          "shipping_details": null,
          "starting_balance": 0,
          "statement_descriptor": null,
          "status": "open",
          "status_transitions": {
            "finalized_at": 1765345618,
            "marked_uncollectible_at": null,
            "paid_at": null,
            "voided_at": null
          },
          "subtotal": 2500,
          "subtotal_excluding_tax": 2500,
          "test_clock": null,
          "total": 2500,
          "total_discount_amounts": [],
          "total_excluding_tax": 2500,
          "total_pretax_credit_amounts": [],
          "total_taxes": [],
          "webhooks_delivered_at": 1765345618
        },
        "livemode": false,
        "metadata": {
          "order_id": "7",
          "plan_id": "2",
          "source_id": "2",
          "user_urn": "urn:sc:users:1001"
        },
        "next_pending_invoice_item_invoice": null,
        "on_behalf_of": null,
        "pause_collection": null,
        "payment_settings": {
          "payment_method_options": null,
          "payment_method_types": null,
          "save_default_payment_method": "on_subscription"
        },
        "pending_invoice_item_interval": null,
        "pending_setup_intent": null,
        "pending_update": null,
        "plan": {
          "id": "price_1ScgEnISIl8QzoFfPzV32FUq",
          "object": "plan",
          "active": true,
          "amount": 2500,
          "amount_decimal": "2500",
          "billing_scheme": "per_unit",
          "created": 1765345617,
          "currency": "usd",
          "interval": "month",
          "interval_count": 1,
          "livemode": false,
          "metadata": {
            "billing_cycle": "monthly",
            "plan_id": "2"
          },
          "meter": null,
          "nickname": null,
          "product": "prod_TZpucAeinrzdzH",
          "tiers_mode": null,
          "transform_usage": null,
          "trial_period_days": null,
          "usage_type": "licensed"
        },
        "quantity": 1,
        "schedule": null,
        "start_date": 1765345618,
        "status": "incomplete",
        "test_clock": null,
        "transfer_data": null,
        "trial_end": null,
        "trial_settings": {
          "end_behavior": {
            "missing_payment_method": "create_invoice"
          }
        },
        "trial_start": null
      }
      """
    +json: array:47 [
      "id" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
      "object" => "subscription"
      "application" => null
      "application_fee_percent" => null
      "automatic_tax" => array:3 [
        "disabled_reason" => null
        "enabled" => false
        "liability" => null
      ]
      "billing_cycle_anchor" => 1765345618
      "billing_cycle_anchor_config" => null
      "billing_mode" => array:2 [
        "flexible" => null
        "type" => "classic"
      ]
      "billing_thresholds" => null
      "cancel_at" => null
      "cancel_at_period_end" => false
      "canceled_at" => null
      "cancellation_details" => array:3 [
        "comment" => null
        "feedback" => null
        "reason" => null
      ]
      "collection_method" => "charge_automatically"
      "created" => 1765345618
      "currency" => "usd"
      "customer" => "cus_TZpBbThDxNdUzj"
      "customer_account" => null
      "days_until_due" => null
      "default_payment_method" => null
      "default_source" => null
      "default_tax_rates" => []
      "description" => null
      "discounts" => []
      "ended_at" => null
      "invoice_settings" => array:2 [
        "account_tax_ids" => null
        "issuer" => array:1 [
          "type" => "self"
        ]
      ]
      "items" => array:5 [
        "object" => "list"
        "data" => array:1 [
          0 => array:13 [
            "id" => "si_TZpuwoMh1mYQmp"
            "object" => "subscription_item"
            "billing_thresholds" => null
            "created" => 1765345618
            "current_period_end" => 1768024018
            "current_period_start" => 1765345618
            "discounts" => []
            "metadata" => []
            "plan" => array:19 [
              "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
              "object" => "plan"
              "active" => true
              "amount" => 2500
              "amount_decimal" => "2500"
              "billing_scheme" => "per_unit"
              "created" => 1765345617
              "currency" => "usd"
              "interval" => "month"
              "interval_count" => 1
              "livemode" => false
              "metadata" => array:2 [
                "billing_cycle" => "monthly"
                "plan_id" => "2"
              ]
              "meter" => null
              "nickname" => null
              "product" => "prod_TZpucAeinrzdzH"
              "tiers_mode" => null
              "transform_usage" => null
              "trial_period_days" => null
              "usage_type" => "licensed"
            ]
            "price" => array:19 [
              "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
              "object" => "price"
              "active" => true
              "billing_scheme" => "per_unit"
              "created" => 1765345617
              "currency" => "usd"
              "custom_unit_amount" => null
              "livemode" => false
              "lookup_key" => null
              "metadata" => array:2 [
                "billing_cycle" => "monthly"
                "plan_id" => "2"
              ]
              "nickname" => null
              "product" => "prod_TZpucAeinrzdzH"
              "recurring" => array:5 [
                "interval" => "month"
                "interval_count" => 1
                "meter" => null
                "trial_period_days" => null
                "usage_type" => "licensed"
              ]
              "tax_behavior" => "unspecified"
              "tiers_mode" => null
              "transform_quantity" => null
              "type" => "recurring"
              "unit_amount" => 2500
              "unit_amount_decimal" => "2500"
            ]
            "quantity" => 1
            "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
            "tax_rates" => []
          ]
        ]
        "has_more" => false
        "total_count" => 1
        "url" => "/v1/subscription_items?subscription=sub_1ScgEoISIl8QzoFfGbttNG7T"
      ]
      "latest_invoice" => array:74 [
        "id" => "in_1ScgEoISIl8QzoFfjn5myIdr"
        "object" => "invoice"
        "account_country" => "AT"
        "account_name" => "Soundcloud "
        "account_tax_ids" => null
        "amount_due" => 2500
        "amount_overpaid" => 0
        "amount_paid" => 0
        "amount_remaining" => 2500
        "amount_shipping" => 0
        "application" => null
        "attempt_count" => 0
        "attempted" => false
        "auto_advance" => false
        "automatic_tax" => array:5 [
          "disabled_reason" => null
          "enabled" => false
          "liability" => null
          "provider" => null
          "status" => null
        ]
        "automatically_finalizes_at" => null
        "billing_reason" => "subscription_create"
        "collection_method" => "charge_automatically"
        "created" => 1765345618
        "currency" => "usd"
        "custom_fields" => null
        "customer" => "cus_TZpBbThDxNdUzj"
        "customer_account" => null
        "customer_address" => null
        "customer_email" => "zijeraw@mailinator.com"
        "customer_name" => "Scarlett Richard"
        "customer_phone" => null
        "customer_shipping" => null
        "customer_tax_exempt" => "none"
        "customer_tax_ids" => []
        "default_payment_method" => null
        "default_source" => null
        "default_tax_rates" => []
        "description" => null
        "discounts" => []
        "due_date" => null
        "effective_at" => 1765345618
        "ending_balance" => 0
        "footer" => null
        "from_invoice" => null
        "hosted_invoice_url" => "https://invoice.stripe.com/i/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ?s=ap"
        "invoice_pdf" => "https://pay.stripe.com/invoice/acct_1RpQw7ISIl8QzoFf/test_YWNjdF8xUnBRdzdJU0lsOFF6b0ZmLF9UWnB1NGUzS1pqM2xqWVpoOG8wM1BIZ3g5OEZZYzk5LDE1NTg4NjQxOQ0200FHnJI1tZ/pdf?s=ap"
        "issuer" => array:1 [
          "type" => "self"
        ]
        "last_finalization_error" => null
        "latest_revision" => null
        "lines" => array:5 [
          "object" => "list"
          "data" => array:1 [
            0 => array:17 [
              "id" => "il_1ScgEoISIl8QzoFfQvZ5R3KJ"
              "object" => "line_item"
              "amount" => 2500
              "currency" => "usd"
              "description" => "1 × Premium Plan - Month Plan (at $25.00 / month)"
              "discount_amounts" => []
              "discountable" => true
              "discounts" => []
              "invoice" => "in_1ScgEoISIl8QzoFfjn5myIdr"
              "livemode" => false
              "metadata" => array:4 [
                "order_id" => "7"
                "plan_id" => "2"
                "source_id" => "2"
                "user_urn" => "urn:sc:users:1001"
              ]
              "parent" => array:3 [
                "invoice_item_details" => null
                "subscription_item_details" => array:5 [
                  "invoice_item" => null
                  "proration" => false
                  "proration_details" => array:1 [
                    "credited_items" => null
                  ]
                  "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
                  "subscription_item" => "si_TZpuwoMh1mYQmp"
                ]
                "type" => "subscription_item_details"
              ]
              "period" => array:2 [
                "end" => 1768024018
                "start" => 1765345618
              ]
              "pretax_credit_amounts" => []
              "pricing" => array:3 [
                "price_details" => array:2 [
                  "price" => "price_1ScgEnISIl8QzoFfPzV32FUq"
                  "product" => "prod_TZpucAeinrzdzH"
                ]
                "type" => "price_details"
                "unit_amount_decimal" => "2500"
              ]
              "quantity" => 1
              "taxes" => []
            ]
          ]
          "has_more" => false
          "total_count" => 1
          "url" => "/v1/invoices/in_1ScgEoISIl8QzoFfjn5myIdr/lines"
        ]
        "livemode" => false
        "metadata" => []
        "next_payment_attempt" => null
        "number" => "OKMWK4LU-0003"
        "on_behalf_of" => null
        "parent" => array:3 [
          "quote_details" => null
          "subscription_details" => array:2 [
            "metadata" => array:4 [
              "order_id" => "7"
              "plan_id" => "2"
              "source_id" => "2"
              "user_urn" => "urn:sc:users:1001"
            ]
            "subscription" => "sub_1ScgEoISIl8QzoFfGbttNG7T"
          ]
          "type" => "subscription_details"
        ]
        "payment_settings" => array:3 [
          "default_mandate" => null
          "payment_method_options" => null
          "payment_method_types" => null
        ]
        "period_end" => 1765345618
        "period_start" => 1765345618
        "post_payment_credit_notes_amount" => 0
        "pre_payment_credit_notes_amount" => 0
        "receipt_number" => null
        "rendering" => null
        "shipping_cost" => null
        "shipping_details" => null
        "starting_balance" => 0
        "statement_descriptor" => null
        "status" => "open"
        "status_transitions" => array:4 [
          "finalized_at" => 1765345618
          "marked_uncollectible_at" => null
          "paid_at" => null
          "voided_at" => null
        ]
        "subtotal" => 2500
        "subtotal_excluding_tax" => 2500
        "test_clock" => null
        "total" => 2500
        "total_discount_amounts" => []
        "total_excluding_tax" => 2500
        "total_pretax_credit_amounts" => []
        "total_taxes" => []
        "webhooks_delivered_at" => 1765345618
      ]
      "livemode" => false
      "metadata" => array:4 [
        "order_id" => "7"
        "plan_id" => "2"
        "source_id" => "2"
        "user_urn" => "urn:sc:users:1001"
      ]
      "next_pending_invoice_item_invoice" => null
      "on_behalf_of" => null
      "pause_collection" => null
      "payment_settings" => array:3 [
        "payment_method_options" => null
        "payment_method_types" => null
        "save_default_payment_method" => "on_subscription"
      ]
      "pending_invoice_item_interval" => null
      "pending_setup_intent" => null
      "pending_update" => null
      "plan" => array:19 [
        "id" => "price_1ScgEnISIl8QzoFfPzV32FUq"
        "object" => "plan"
        "active" => true
        "amount" => 2500
        "amount_decimal" => "2500"
        "billing_scheme" => "per_unit"
        "created" => 1765345617
        "currency" => "usd"
        "interval" => "month"
        "interval_count" => 1
        "livemode" => false
        "metadata" => array:2 [
          "billing_cycle" => "monthly"
          "plan_id" => "2"
        ]
        "meter" => null
        "nickname" => null
        "product" => "prod_TZpucAeinrzdzH"
        "tiers_mode" => null
        "transform_usage" => null
        "trial_period_days" => null
        "usage_type" => "licensed"
      ]
      "quantity" => 1
      "schedule" => null
      "start_date" => 1765345618
      "status" => "incomplete"
      "test_clock" => null
      "transfer_data" => null
      "trial_end" => null
      "trial_settings" => array:1 [
        "end_behavior" => array:1 [
          "missing_payment_method" => "create_invoice"
        ]
      ]
      "trial_start" => null
    ]
    +code: 200
  }
  +saveWithParent: false
  id: "sub_1ScgEoISIl8QzoFfGbttNG7T"
  object: "subscription"
  application: null
  application_fee_percent: null
  automatic_tax: 
Stripe
\
StripeObject
 {#1806}
  billing_cycle_anchor: 1765345618
  billing_cycle_anchor_config: null
  billing_mode: 
Stripe
\
StripeObject
 {#1801}
  billing_thresholds: null
  cancel_at: null
  cancel_at_period_end: false
  canceled_at: null
  cancellation_details: 
Stripe
\
StripeObject
 {#1798}
  collection_method: "charge_automatically"
  created: 1765345618
  currency: "usd"
  customer: "cus_TZpBbThDxNdUzj"
  customer_account: null
  days_until_due: null
  default_payment_method: null
  default_source: null
  default_tax_rates: []
  description: null
  discounts: []
  ended_at: null
  invoice_settings: 
Stripe
\
StripeObject
 {#1794}
  items: 
Stripe
\
Collection
 {#1743}
  latest_invoice: 
Stripe
\
Invoice
 {#1748}
  livemode: false
  metadata: 
Stripe
\
StripeObject
 {#1815}
  next_pending_invoice_item_invoice: null
  on_behalf_of: null
  pause_collection: null
  payment_settings: 
Stripe
\
StripeObject
 {#1911}
  pending_invoice_item_interval: null
  pending_setup_intent: null
  pending_update: null
  plan: 
Stripe
\
Plan
 {#1918}
  quantity: 1
  schedule: null
  start_date: 1765345618
  status: "incomplete"
  test_clock: null
  transfer_data: null
  trial_end: null
  trial_settings: 
Stripe
\
StripeObject
 {#1922}
  trial_start: null
}