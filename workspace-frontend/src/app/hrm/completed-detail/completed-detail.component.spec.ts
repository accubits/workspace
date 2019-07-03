import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CompletedDetailComponent } from './completed-detail.component';

describe('CompletedDetailComponent', () => {
  let component: CompletedDetailComponent;
  let fixture: ComponentFixture<CompletedDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CompletedDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CompletedDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
