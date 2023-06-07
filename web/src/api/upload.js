import request from '@/utils/request'

export function handleUploadSuccess(response, files, fileList) {
  console.log(response)
  if (response.ret !== 'success') {
    fileList = []
    this.$message.error({
      message: response.msg
    })
  } else {
    fileList.push({
      name: response.savename,
      url: response.url
    })
    console.log(fileList)
    console.log(files)
  }
}
