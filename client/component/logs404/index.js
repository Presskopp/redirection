/**
 * External dependencies
 */

import React from 'react';
import { connect } from 'react-redux';
import { translate as __ } from 'lib/locale';

/**
 * Internal dependencies
 */

import Table from 'component/table';
import { loadLogs, deleteAll } from 'state/log/action';
import DeleteAll from 'component/logs/delete-all';
import ExportCSV from 'component/logs/export-csv';
import { LOGS_TYPE_404 } from 'state/log/type';
import Row404 from './row';

class Logs404 extends React.Component {
	constructor( props ) {
		super( props );

		props.onLoad( LOGS_TYPE_404 );
	}

	renderRow( row, key, status ) {
		return <Row404 item={ row } key={ key } selected={ status.isSelected } isLoading={ status.isLoading } />;
	}

	render() {
		const headers = [
			{
				name: 'cb',
				check: true,
			},
			{
				name: 'date',
				title: __( 'Date' ),
			},
			{
				name: 'url',
				title: __( 'Source URL' ),
			},
			{
				name: 'referrer',
				title: __( 'Referrer' ),
			},
			{
				name: 'ip',
				title: __( 'IP' ),
				sortable: false,
			},
		];

		return (
			<div>
				<Table headers={ headers } row={ this.renderRow } store={ this.props.log } />

				<br />
				<DeleteAll onDelete={ this.props.onDeleteAll } />
				<br />
				<ExportCSV logType={ LOGS_TYPE_404 } />
			</div>
		);
	}
}

function mapStateToProps( state ) {
	const { log } = state;

	return {
		log,
	};
}

function mapDispatchToProps( dispatch ) {
	return {
		onLoad: logType => {
			dispatch( loadLogs( logType ) );
		},
		onDeleteAll: () => {
			dispatch( deleteAll() );
		},
	};
}

export default connect(
	mapStateToProps,
	mapDispatchToProps
)( Logs404 );