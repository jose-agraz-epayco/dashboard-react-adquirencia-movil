import { DataService } from '../../services/dataService'
export const SET_STATE_GLOBAL = 'SET_STATE_GLOBAL'

const dataService = new DataService()

export const setToken = (token: any) => (dispatch: any) => {
  const valid = dataService.validateRecaptcha(token)
  dispatch({
    type: SET_STATE_GLOBAL,
    payload: token,
  })
}