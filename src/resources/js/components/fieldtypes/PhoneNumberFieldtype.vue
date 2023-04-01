<template>
    <div>
        <div class="flex items-center">
            <div class="input-group">
                <div class="input-group-prepend" v-if="prepend" v-text="prepend" />
                <input
                    ref="input"
                    class="input-text"
                    :class="classes"
                    :id="id"
                    :name="name"
                    :value="value"
                    :step="step"
                    :disabled="disabled"
                    :readonly="isReadOnly"
                    :placeholder="placeholder"
                    :autofocus="focus"
                    @input="inputEvent($event)"
                    @keydown="$emit('keydown', $event)"
                    @focus="$emit('focus')"
                    @blur="$emit('blur')">
                <div class="input-group-append" v-if="append" v-text="append" />
            </div>
        </div>
        <div class="help-block text-red mt-1 mb-0" v-if="errorMessage" v-text="errorMessage" />
    </div>
</template>

<style>

    .iti {
        width: 100%;
        box-shadow: inset 0 1px 1px 0 rgba(0,0,0,.05);
    }

    .iti .input-text {
        border-top-right-radius: 3px !important;
        border-bottom-right-radius: 3px !important;
        border-right-width: 1px !important;
        border-top-left-radius: 3px !important;
        border-bottom-left-radius: 3px !important;
        border-left-width: 1px !important;
    }

    .iti__flag {
        background-image: url('intl-tel-input/build/img/flags.png');
    }

    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .iti__flag {
            background-image: url('intl-tel-input/build/img/flags@2x.png');
        }
    }

    .iti__country-list {
        z-index: 10;
    }

</style>

<script>

import 'intl-tel-input/build/css/intlTelInput.css';
import intlTelInput from 'intl-tel-input';

export default {
    mixins: [Fieldtype],
    props: {
        name: {},
        disabled: { default: false },
        classes: { default: null },
        id: { default: null },
        isReadOnly: { type: Boolean, default: false },
        placeholder: { required: false },
        step: {},
        value: { required: true },
        prepend: { default: null },
        append: { default: null },
        focus: { type: Boolean },
        autoselect: { type: Boolean }
    },
    data() {
        return {
            iti: null,
            errors: {
                // The number has an invalid country calling code.
                INVALID_COUNTRY_CODE: 'Invalid country calling code',

                // This indicates the string passed is not a valid number. Either the string
                // had less than 3 digits in it or had an invalid phone-context parameter.
                // More specifically, the number failed to match the regular expression
                // VALID_PHONE_NUMBER, RFC3966_GLOBAL_NUMBER_DIGITS, or RFC3966_DOMAINNAME.
                NOT_A_NUMBER: 'This number did not seem to be a phone number',

                // This indicates the string started with an international dialing prefix, but
                // after this was stripped from the number, had less digits than any valid
                // phone number (including country calling code) could have.
                TOO_SHORT_AFTER_IDD: 'This phone number too short after international prefix (IDD).',

                // This indicates the string, after any country calling code has been
                // stripped, had less digits than any valid phone number could have.
                TOO_SHORT_NSN: 'This number is too short to be a phone number.',

                // This indicates the string had more digits than any valid phone number could
                // have.
                TOO_LONG: 'This number is too long to be a phone number.',
                
                // The number is longer than the shortest valid numbers for this region,
                // shorter than the longest valid numbers for this region, and does not itself
                // have a number length that matches valid numbers for this region.
                // This can also be returned in the case where
                // isPossibleNumberForTypeWithReason was called, and there are no numbers of
                // this type at all for this region.
                INVALID_LENGTH: 'The number is longer than all valid numbers for this region.',
            },
            errorMessage: null,
        }
    },
    async mounted() {
        this.componentValue = this.value;

        const currentLang = document.documentElement.lang;
        let localizedCountries = null;
        if (currentLang !== 'en') {
            let localizedCountriesResponse = await fetch(`/!/statamic-phone-number-fieldtype/${currentLang}/countries`);
            localizedCountriesResponse = await localizedCountriesResponse.json();
            localizedCountries = await localizedCountriesResponse;
        }

        this.iti = intlTelInput(this.$refs.input, {
            allowDropdown: this.config.show_country_select ?? true,
            initialCountry: this.config.initial_country ?? null,
            excludeCountries: this.config.exclude_countries ?? [],
            onlyCountries: this.config.only_countries ?? [],
            preferredCountries: this.config.preferred_countries ?? ['us', 'gb'],
            localizedCountries: localizedCountries,
            utilsScript: '/vendor/statamic-phone-number-fieldtype/js/utils.js',
        });

        if (this.autoselect) {
            this.$refs.input.select();
        }

        if (this.focus) {
            this.$refs.input.focus();
        }
    },
    methods: {
        inputEvent($event) {

            const validationError = this.iti.getValidationError();

            switch(validationError) {

                case intlTelInputUtils.validationError.IS_POSSIBLE:
                case intlTelInputUtils.validationError.IS_POSSIBLE_LOCAL_ONLY:
                    this.errorMessage = null;
                    break;

                case intlTelInputUtils.validationError.INVALID_COUNTRY_CODE:
                    this.errorMessage = this.errors.INVALID_COUNTRY_CODE;
                    break;

                case intlTelInputUtils.validationError.INVALID_LENGTH:
                    this.errorMessage = this.errors.INVALID_LENGTH;
                    break;

                case intlTelInputUtils.validationError.TOO_LONG:
                    this.errorMessage = this.errors.TOO_LONG;
                    break;

                case intlTelInputUtils.validationError.TOO_SHORT_AFTER_IDD:
                    this.errorMessage = this.errors.TOO_SHORT_AFTER_IDD;
                    break;

                case intlTelInputUtils.validationError.TOO_SHORT_NSN:
                    this.errorMessage = this.errors.TOO_SHORT_NSN;
                    break;
            }

            if (null === this.errorMessage) {
                this.value = this.iti.getNumber(intlTelInputUtils.numberFormat.E164);
                this.$emit('input', this.value);
                
                return;
            }

            this.$emit('input', $event.target.value);
        },
    }
};

</script>
