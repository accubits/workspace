import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddNewAbsenceComponent } from './add-new-absence.component';

describe('AddNewAbsenceComponent', () => {
  let component: AddNewAbsenceComponent;
  let fixture: ComponentFixture<AddNewAbsenceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddNewAbsenceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddNewAbsenceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
