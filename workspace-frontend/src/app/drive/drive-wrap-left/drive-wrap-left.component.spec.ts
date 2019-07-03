import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DriveWrapLeftComponent } from './drive-wrap-left.component';

describe('DriveWrapLeftComponent', () => {
  let component: DriveWrapLeftComponent;
  let fixture: ComponentFixture<DriveWrapLeftComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DriveWrapLeftComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DriveWrapLeftComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
