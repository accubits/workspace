import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RequestTableHeadComponent } from './request-table-head.component';

describe('RequestTableHeadComponent', () => {
  let component: RequestTableHeadComponent;
  let fixture: ComponentFixture<RequestTableHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RequestTableHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RequestTableHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
