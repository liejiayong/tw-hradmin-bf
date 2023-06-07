import request from '@/utils/request'

export function addJobSeekerMaterial(data) {
  return request({
    url: '',
    method: 'post',
    data,
    params: {
      c: 'JobSeekerMaterial',
      m: 'add'
    }
  }).then(res => {
    return res
  })
}

export function updateJobSeekerMaterial(data) {
  return request({
    url: '',
    method: 'post',
    data,
    params: {
      c: 'JobSeekerMaterial',
      m: 'update'
    }
  }).then(res => {
    return res
  })
}

export function deleteJobSeekerMaterial(data) {
  return request({
    url: '',
    method: 'post',
    data,
    params: {
      c: 'JobSeekerMaterial',
      m: 'delete'
    }
  }).then(res => {
    return res
  })
}

export function getList(data) {
  return request({
    url: '',
    method: 'post',
    data,
    params: {
      c: 'JobSeekerMaterial',
      m: 'index'
    }
  }).then(res => {
    return res
  })
}
