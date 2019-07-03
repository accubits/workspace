import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DriveWrapRightComponent } from './drive-wrap-right.component';

describe('DriveWrapRightComponent', () => {
  let component: DriveWrapRightComponent;
  let fixture: ComponentFixture<DriveWrapRightComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DriveWrapRightComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DriveWrapRightComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
