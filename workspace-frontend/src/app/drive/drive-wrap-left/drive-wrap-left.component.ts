import { Component, OnInit } from '@angular/core';
import { DriveDataService } from '../../shared/services/drive-data.service';
import { DriveSandbox } from '../drive.sandbox';
import { Router } from '@angular/router';

@Component({
  selector: 'app-drive-wrap-left',
  templateUrl: './drive-wrap-left.component.html',
  styleUrls: ['./drive-wrap-left.component.scss']
})
export class DriveWrapLeftComponent implements OnInit {
  constructor(
    public driveDataService: DriveDataService,
    private driveSandbox: DriveSandbox,
    private router: Router) { }

  ngOnInit() {
   }

  /* drive selecting[start] */
  selectedDrive(selectedDrive) {
    this.driveDataService.resetDriveFiles();
    this.driveDataService.resetDrivePopUps();
    this.driveDataService.getDriveFiles.selectedTab = selectedDrive.displayName;
    this.driveDataService.getDriveFiles.selectedDriveSlug = selectedDrive.slug;
    this.router.navigate([], { queryParams: { slug: this.driveDataService.getDriveFiles.selectedDriveSlug } });
    if (selectedDrive.routerLink === 'trash' || selectedDrive.routerLink === 'share-with-me') {
      this.driveDataService.uploadBtn.show = false;
    }
    else {
      this.driveDataService.uploadBtn.show = true;
    }
  }
  /* drive selecting[end] */
}
