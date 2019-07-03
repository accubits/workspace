import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EditEmpProfileComponent } from './edit-emp-profile.component';

describe('EditEmpProfileComponent', () => {
  let component: EditEmpProfileComponent;
  let fixture: ComponentFixture<EditEmpProfileComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EditEmpProfileComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EditEmpProfileComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
