import js from '@eslint/js';
import globals from 'globals';

export default [
	js.configs.recommended,
	{
		files: ['assets/js/**/*.js'],
		languageOptions: {
			ecmaVersion: 2020,
			globals: {
				...globals.browser,
				wp: 'readonly',
			},
		},
		rules: {
			'no-unused-vars': 'warn',
			'no-console': 'warn',
			'eqeqeq': 'error',
			'semi': ['error', 'always'],
		},
	},
];
