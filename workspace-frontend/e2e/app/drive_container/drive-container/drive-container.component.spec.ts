import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DriveContainerComponent } from './drive-container.component';

describe('DriveContainerComponent', () => {
  let component: DriveContainerComponent;
  let fixture: ComponentFixture<DriveContainerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DriveContainerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DriveContainerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
