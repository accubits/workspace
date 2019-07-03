import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DriveTableComponent } from './drive-table.component';

describe('DriveTableComponent', () => {
  let component: DriveTableComponent;
  let fixture: ComponentFixture<DriveTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DriveTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DriveTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
