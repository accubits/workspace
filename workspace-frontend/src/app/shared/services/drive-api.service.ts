import { Injectable } from '@angular/core';
import { Observable } from "rxjs/Observable";
import { Configs } from '../../config';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { DriveDataService } from './drive-data.service';
import { CookieService } from 'ngx-cookie-service';

@Injectable()
export class DriveApiService {
  constructor(private http: HttpClient,
    private cookieService: CookieService,
    public driveDataService: DriveDataService) { }

  /* API call for get drive type[Start] */
  getDriveTypeList(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/list-drive-types';
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.get(URL, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for get drive type[end] */

  /* API call for get drive files[Start] */
  getFileList(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/fetchall';
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "driveTypeSlug": this.driveDataService.getDriveFiles.selectedDriveSlug,
      "folderSlug": this.driveDataService.driveFileManagement.sourceSlug,
      "sortBy": this.driveDataService.driveFileManagement.sortOption,
      "sortOrder": this.driveDataService.driveFileManagement.sortMethod
    }
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for get drive files[end] */


   /* API call for get drive files[Start] */
   destinationFolderList(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/fetchall';
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "driveTypeSlug": this.driveDataService.getDriveFiles.selectedDriveSlug,
      "folderSlug": this.driveDataService.driveFileManagement.destinationSlug,
      "sortBy": this.driveDataService.driveFileManagement.sortOption,
      "sortOrder": this.driveDataService.driveFileManagement.sortMethod
    }
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for get drive files[end] */

/* get all departments [Start] */
getAllDepartment(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/fetchAllDepartments'
  let data = {
    'orgSlug': this.cookieService.get('orgSlug'),
   }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get all departments [end] */

  /* API call for get drive get User List[Start] */
  getUserList(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/orguserlist';
    let header = new HttpHeaders().set('row', 'application/json');
    let headers = { headers: header };
    let data = {
      "org_slug": this.cookieService.get('orgSlug'),
      "q": this.driveDataService.driveFileManagement.searchUser
    }
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for get drive get User List[end] */

  /* API call for search files[Start] */
  getSearchFile(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/fetchall';
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "driveTypeSlug": this.driveDataService.getDriveFiles.selectedDriveSlug,
      "folderSlug": this.driveDataService.driveFileManagement.sourceSlug,
      "q": this.driveDataService.driveFileManagement.searchText
    }
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for search files[end] */

  /* API call for upload file[Start] */
  uploadFile(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/upload-file';
    let header = new HttpHeaders().set('row', 'application/json');
    let headers = { headers: header };
    var formData = new FormData();
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "folderSlug": this.driveDataService.driveFileManagement.sourceSlug,
      "driveTypeSlug": this.driveDataService.getDriveFiles.selectedDriveSlug
    }
    formData.append("data", JSON.stringify(data));
    formData.append("file[]", this.driveDataService.driveFileManagement.uploadFiles[0]);
    return this.http.post(URL, formData, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for upload file[end] */

  /* API call for create folder[Start] */
  createFolder(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/new-folder';
    let header = new HttpHeaders().set('raw', 'application/json');
    let headers = { headers: header };
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "driveTypeSlug": this.driveDataService.getDriveFiles.selectedDriveSlug,
      "folderSlug": this.driveDataService.driveFileManagement.destinationSlug,
      "folderName": this.driveDataService.driveFileManagement.folderName
    }
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }

  /* API call for create folder[end] */

  /* API call for delete file from folder[Start] */
  deleteFiles(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/delete-files';
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "driveTypeSlug": this.driveDataService.getDriveFiles.selectedDriveSlug,
      "deleteFiles": this.driveDataService.driveFileManagement.selectedFileSlug
    }
    let header = new HttpHeaders().set('raw', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for delete file from folder[end] */

  /* API call for delete file from trash[Start] */
  deleteTrashFile(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/delete-restore-trashed-files';
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "fileSlug": this.driveDataService.driveFileManagement.selectedFileSlug,
      "action": this.driveDataService.driveFileManagement.trashAction
    }
    let header = new HttpHeaders().set('raw', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for delete file from trash[end] */

  /* API call for copy file[Start] */
  copyFile(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/copy-file';
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "driveTypeSlug": this.driveDataService.getDriveFiles.selectedDriveSlug,
      "sourceSlug": this.driveDataService.driveFileManagement.selectedFileSlug,
      "destinationSlug": this.driveDataService.driveFileManagement.destinationSlug
    }
    let header = new HttpHeaders().set('raw', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for copy file[end] */

  /* API call for share file[Start] */
  shareFile(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/share';
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "elementSlug": this.driveDataService.driveFileManagement.selectedFileSlug,
      "sharedUsers": this.driveDataService.driveFileManagement.shearedUserList,
      "sharedDepartment": this.driveDataService.driveFileManagement.shearedDeptList
    }
    let header = new HttpHeaders().set('raw', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for share file[end] */

  /* API call for move file[Start] */
  moveFile(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/move-file';
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "driveTypeSlug": this.driveDataService.getDriveFiles.selectedDriveSlug,
      "sourceSlug": this.driveDataService.driveFileManagement.selectedFileSlug,
      "destinationSlug": this.driveDataService.driveFileManagement.destinationSlug
    }
    let header = new HttpHeaders().set('raw', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for move file[end] */

  /* API call for rename file[Start] */
  renameFile(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'drive/rename-file';
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "driveTypeSlug": this.driveDataService.getDriveFiles.selectedDriveSlug,
      "elementSlug": this.driveDataService.driveFileManagement.selectedFileSlug,
      "elementRename": this.driveDataService.driveFileManagement.newFileName
    }
    let header = new HttpHeaders().set('raw', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for rename file[end] */

  /* Generic function to check Responses[Start] */
  checkResponse(response: any) {
    let results = response
    if (results.status) {
      return results;
    }
    else {
      console.log("Error in API");
      return results;
    }
  }
  /* Generic function to check Responses[End] */
}
