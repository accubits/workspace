import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SubAdminTableComponent } from './sub-admin-table.component';

describe('SubAdminTableComponent', () => {
  let component: SubAdminTableComponent;
  let fixture: ComponentFixture<SubAdminTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SubAdminTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SubAdminTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
