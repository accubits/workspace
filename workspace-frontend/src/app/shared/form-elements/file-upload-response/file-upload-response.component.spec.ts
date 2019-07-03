import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FileUploadResponseComponent } from './file-upload-response.component';

describe('FileUploadResponseComponent', () => {
  let component: FileUploadResponseComponent;
  let fixture: ComponentFixture<FileUploadResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FileUploadResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FileUploadResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
