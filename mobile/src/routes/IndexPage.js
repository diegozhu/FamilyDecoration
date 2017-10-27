import { connect } from 'dva';
import styles from './IndexPage.less';
import React, { Component } from 'react';
import { PropTypes } from 'prop-types';
import TabCt from '../components/tabct';
import { withRouter } from 'react-router-dom';

class IndexPage extends Component {
  render() {
    const { match, location, history, dispatch, example } = this.props;
    debugger

    const tabs = (
      <TabCt
        onTabClick={
          (tab, index) => {
            dispatch({
              type: 'example/fetch',
              payload: {
                test: 1
              }
            });
          }
        }
        example={example}
      />
    );
    return tabs;
  }
}

IndexPage.propTypes = {
  match: PropTypes.object.isRequired,
  history: PropTypes.object.isRequired,
  location: PropTypes.object.isRequired,
};

export default connect()(withRouter(IndexPage));
