import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RequestDetailPopComponent } from './request-detail-pop.component';

describe('RequestDetailPopComponent', () => {
  let component: RequestDetailPopComponent;
  let fixture: ComponentFixture<RequestDetailPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RequestDetailPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RequestDetailPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
