import styled from 'styled-components'
import { TABLET, MOBILE } from '../../styles/breakpoints'

export const Header = styled.header`
  background: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e0e0e0;
  position: relative;
  width: 100%;
  height: 3rem;
  min-width: 335px;
  @media (max-width: ${MOBILE}) {
    width: 100vw;
  }
  
`
export const ContainerButtonMenu = styled.div`
    margin: 0 1rem;
    button {
      display: none;
    }
    @media (max-width: ${TABLET}) {
      button {
        display:${(props: any) => (props['data-sidebar'] ? 'none' : 'block')};
        /* display: block; */
        position: relative;
        z-index: 1000;
        border-radius: 4px;
        font-size: 25px;
        border: 1px solid #e4e4e4;
        padding-top: 0.3rem;
        cursor: pointer;
        :hover {
          background-color: #e6e6e6;
        }
      }
    }
`

export const ContenSidebar = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;
`

export const AvatarImg = styled.div`
  display: flex;
  align-items: center;
`

export const ContainerUser = styled.div`
    display: flex;
    color: #cecece;
    font-size: 35px;
    margin: 0 1rem;
`

export const DropdownUser = styled.div`
    height: 3rem;
    top: 2.5rem;
    align-items: center;
    width: 3rem;
    a{
      font-size: 16px;
    }
`
export const ContainermenuBreadcrumbs = styled.div`
    display: flex;
    align-items: center;
`
export const ContentBreadcrumbs = styled.div`
    margin-left: -1rem;
    @media (max-width: ${TABLET}) {
      margin-left: 0;
    }
`